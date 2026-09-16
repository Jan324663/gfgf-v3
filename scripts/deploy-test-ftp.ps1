param(
    [string]$CredentialFile = "C:\Users\info\dev\GFGF.org V3 Codex\ftp.txt",
    [string]$RemoteRoot = "/httpdocs"
)

$ErrorActionPreference = "Stop"

function Get-FtpCredentials {
    param([string]$Path)

    if (-not (Test-Path -LiteralPath $Path)) {
        throw "FTP credential file not found: $Path"
    }

    $text = Get-Content -LiteralPath $Path -Raw
    $hostMatch = [regex]::Match($text, "IP-Adresse\s+([0-9.]+)")
    $userMatch = [regex]::Match($text, "user:\s*(\S+)")
    $passMatch = [regex]::Match($text, "pw:\s*(.+)")

    if (-not ($hostMatch.Success -and $userMatch.Success -and $passMatch.Success)) {
        throw "Could not parse FTP credential file: $Path"
    }

    [pscustomobject]@{
        Host = $hostMatch.Groups[1].Value.Trim()
        User = $userMatch.Groups[1].Value.Trim()
        Pass = $passMatch.Groups[1].Value.Trim()
    }
}

function Ensure-FtpDirectory {
    param(
        [string]$FtpHost,
        [string]$User,
        [string]$Pass,
        [string]$RemotePath
    )

    $parts = $RemotePath.Trim("/").Split("/", [System.StringSplitOptions]::RemoveEmptyEntries)
    $current = ""
    foreach ($part in $parts) {
        $current = "$current/$part"
        & curl.exe --noproxy "*" --silent --show-error --ftp-create-dirs --user "${User}:${Pass}" "ftp://${FtpHost}${current}/" | Out-Null
    }
}

function Send-FtpDirectory {
    param(
        [string]$LocalPath,
        [string]$RemotePath,
        [string]$FtpHost,
        [string]$User,
        [string]$Pass
    )

    Ensure-FtpDirectory -FtpHost $FtpHost -User $User -Pass $Pass -RemotePath $RemotePath

    Get-ChildItem -LiteralPath $LocalPath -Recurse -File | ForEach-Object {
        $relative = $_.FullName.Substring($LocalPath.Length).TrimStart("\", "/")
        $remoteFile = ($RemotePath.TrimEnd("/") + "/" + ($relative -replace "\\", "/"))
        $remoteDir = Split-Path -Parent $remoteFile
        $remoteDir = $remoteDir -replace "\\", "/"

        Ensure-FtpDirectory -FtpHost $FtpHost -User $User -Pass $Pass -RemotePath $remoteDir
        & curl.exe --noproxy "*" --silent --show-error --ftp-create-dirs --user "${User}:${Pass}" --upload-file $_.FullName "ftp://${FtpHost}${remoteFile}"
        Write-Host "Uploaded $relative"
    }
}

$RepoRoot = Resolve-Path (Join-Path $PSScriptRoot "..")
$creds = Get-FtpCredentials -Path $CredentialFile

$deployItems = @(
    @{ Local = Join-Path $RepoRoot "wp-content\themes\gfgf-v3"; Remote = "$RemoteRoot/wp-content/themes/gfgf-v3" },
    @{ Local = Join-Path $RepoRoot "wp-content\plugins\gfgf-service-docs"; Remote = "$RemoteRoot/wp-content/plugins/gfgf-service-docs" }
)

foreach ($item in $deployItems) {
    if (-not (Test-Path -LiteralPath $item.Local)) {
        throw "Local deploy path missing: $($item.Local)"
    }
    Send-FtpDirectory -LocalPath $item.Local -RemotePath $item.Remote -FtpHost $creds.Host -User $creds.User -Pass $creds.Pass
}
