#!/usr/bin/env bash
# ─────────────────────────────────────────────────────────────────────
# apply-claude-rtl.sh
#
# Adds RTL direction to the Claude Code VSCode extension's chat panel
# (for comfortable Hebrew/Arabic conversations). Keeps code blocks LTR.
#
# Usage:
#   bash apply-claude-rtl.sh apply    # turn RTL on  (default)
#   bash apply-claude-rtl.sh revert   # turn RTL off (restore backup)
#   bash apply-claude-rtl.sh status   # check if applied
#
# After running:  VSCode → Ctrl+Shift+P → "Developer: Reload Window"
#
# Portable across:
#   - Windows VSCode (-win32-x64)
#   - Windows VSCode Insiders (.vscode-insiders dir)
#   - macOS Apple Silicon (-darwin-arm64)
#   - macOS Intel (-darwin-x64)
#   - Linux (-linux-x64 / -linux-arm64)
#
# Auto-finds the latest installed Claude Code version, so it survives
# extension updates. Just re-run after any Claude Code update.
# ─────────────────────────────────────────────────────────────────────

set -e

MARKER="RTL override for Hebrew/Arabic users"

# Try common VSCode extension dirs in order: stable, insiders, cursor
EXT_DIR_CANDIDATES=(
    "$HOME/.vscode/extensions"
    "$HOME/.vscode-insiders/extensions"
    "$HOME/.cursor/extensions"
)

# Try all known Claude Code arch suffixes
ARCH_GLOBS=(
    "anthropic.claude-code-*-win32-x64"
    "anthropic.claude-code-*-darwin-arm64"
    "anthropic.claude-code-*-darwin-x64"
    "anthropic.claude-code-*-linux-x64"
    "anthropic.claude-code-*-linux-arm64"
    "anthropic.claude-code-*"
)

find_latest_ext() {
    local found=""
    for base in "${EXT_DIR_CANDIDATES[@]}"; do
        [ -d "$base" ] || continue
        for glob in "${ARCH_GLOBS[@]}"; do
            local match
            match=$(ls -d "$base"/$glob 2>/dev/null | sort -V | tail -1)
            if [ -n "$match" ] && [ -d "$match/webview" ]; then
                echo "$match"
                return 0
            fi
        done
    done
    return 1
}

EXT_DIR=$(find_latest_ext)
if [ -z "$EXT_DIR" ]; then
    echo "ERROR: Claude Code extension not found in any of:" >&2
    printf '  %s\n' "${EXT_DIR_CANDIDATES[@]}" >&2
    echo ""
    echo "Searched globs: ${ARCH_GLOBS[*]}" >&2
    exit 1
fi

CSS="$EXT_DIR/webview/index.css"
BAK="$EXT_DIR/webview/index.css.bak-rtl"

if [ ! -f "$CSS" ]; then
    echo "ERROR: $CSS not found (extension may be incompatible)" >&2
    exit 1
fi

ACTION="${1:-apply}"
echo "Extension: $EXT_DIR"
echo "Action:    $ACTION"
echo ""

case "$ACTION" in
    apply)
        if grep -q "$MARKER" "$CSS"; then
            echo "✓ Already applied (marker found in index.css)."
            exit 0
        fi
        if [ ! -f "$BAK" ]; then
            cp "$CSS" "$BAK"
            echo "✓ Backup created: $BAK"
        else
            echo "  Backup already exists: $BAK"
        fi
        cat >> "$CSS" <<'EOF'

/* ===================================================================
   RTL override for Hebrew/Arabic users
   To revert: bash apply-claude-rtl.sh revert
   ================================================================= */
[class*="chatContainer"],
[class*="messagesContainer"],
[class*="messageContainer"],
[class*="message_"],
[class*="message-container"],
[class*="messageInput"],
[class*="messageInputContainer"] {
    direction: rtl !important;
    text-align: right !important;
    unicode-bidi: plaintext;
}

/* Keep code blocks, terminals, monaco editor LTR (don't flip code) */
[class*="message"] pre,
[class*="message"] code,
[class*="message"] kbd,
[class*="codeBlock"],
.monaco-editor,
.monaco-editor *,
[class*="terminal"],
[class*="diff"],
[class*="codeMirror"] {
    direction: ltr !important;
    text-align: left !important;
    unicode-bidi: isolate;
}
EOF
        echo "✓ RTL CSS appended to $CSS"
        echo ""
        echo "Next: VSCode → Ctrl+Shift+P → \"Developer: Reload Window\""
        ;;

    revert)
        if [ ! -f "$BAK" ]; then
            echo "ERROR: backup not found at $BAK" >&2
            echo "Cannot revert automatically. The extension is probably already clean." >&2
            exit 1
        fi
        cp "$BAK" "$CSS"
        echo "✓ Restored $CSS from backup."
        echo ""
        echo "Next: VSCode → Ctrl+Shift+P → \"Developer: Reload Window\""
        ;;

    status)
        if grep -q "$MARKER" "$CSS"; then
            echo "Status: APPLIED ✓"
            echo "  Marker found in $CSS"
            [ -f "$BAK" ] && echo "  Backup available at $BAK"
        else
            echo "Status: NOT applied"
            [ -f "$BAK" ] && echo "  (But backup exists at $BAK — was applied previously)"
        fi
        ;;

    *)
        echo "Usage: $0 [apply|revert|status]"
        echo ""
        echo "  apply   - add RTL CSS to chat panel"
        echo "  revert  - restore original CSS from backup"
        echo "  status  - check whether RTL is currently applied"
        exit 1
        ;;
esac
