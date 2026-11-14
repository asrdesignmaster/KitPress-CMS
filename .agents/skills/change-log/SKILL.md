---
name: change-log
description: >-
  Maintain a project change log in CHANGES_LOG.md at the repository root.
  Use and update this log after every code, config, UI, or file change made in KitPress CMS.
---

# KitPress CMS Change Log Skill

Whenever you make any code, configuration, UI, database, or documentation change in this project, you **must** update `CHANGES_LOG.md` at the project root before finishing your response.

## Log File Location

- `CHANGES_LOG.md` (at the repository root)

## Entry Format

Add new entries at the **top** of the log (right under the `## Change Log` heading) so the most recent changes appear first:

```markdown
### YYYY-MM-DD HH:MM — <Short Title>
- **Summary**: Concise description of what was changed and why.
- **Files**:
  - `path/to/file1` — what changed
  - `path/to/file2` — what changed
- **Verification**: Commands/builds/checks run (or `Not run` if skipped).
```

## Rules

1. Update `CHANGES_LOG.md` **after every change** (bug fixes, new features, UI/UX tweaks, refactors, config updates).
2. Keep entries accurate — only list files actually modified and checks actually run.
3. Do not delete previous log entries unless the user explicitly asks to prune or reset the log.
