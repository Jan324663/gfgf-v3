param(
    [string]$LocalWpPath = "C:\laragon\www\gfgf-v3"
)

$ErrorActionPreference = "Stop"

$RepoRoot = Resolve-Path (Join-Path $PSScriptRoot "..")
$SourceContent = Join-Path $RepoRoot "wp-content"
$TargetContent = Join-Path $LocalWpPath "wp-content"

if (-not (Test-Path -LiteralPath $LocalWpPath)) {
    throw "Local WordPress path does not exist: $LocalWpPath"
}

if (-not (Test-Path -LiteralPath $TargetContent)) {
    throw "Target wp-content path does not exist: $TargetContent"
}

$items = @(
    @{ Source = "themes\gfgf-v3"; Target = "themes\gfgf-v3" },
    @{ Source = "plugins\gfgf-service-docs"; Target = "plugins\gfgf-service-docs" }
)

foreach ($item in $items) {
    $src = Join-Path $SourceContent $item.Source
    $dst = Join-Path $TargetContent $item.Target

    if (-not (Test-Path -LiteralPath $src)) {
        throw "Source path missing: $src"
    }

    if (Test-Path -LiteralPath $dst) {
        Remove-Item -LiteralPath $dst -Recurse -Force
    }

    $parent = Split-Path -Parent $dst
    New-Item -ItemType Directory -Force -Path $parent | Out-Null
    Copy-Item -LiteralPath $src -Destination $dst -Recurse
    Write-Host "Synced $($item.Source) -> $dst"
}

