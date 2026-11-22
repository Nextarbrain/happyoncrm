# Agent instructions

Before doing anything, read `PROJECT_CONTEXT.md` in the repo root in full. It is the single source of truth for this project: goal, stack, how to run, modules, decisions, roadmap and the round log.

Rules for every AI coding tool (Antigravity/agy, Claude Code, Codex, Gemini, Cursor, etc.):
- Work on a branch, never directly on main. Do not merge without the owner's approval.
- Never commit or print secrets (.env, passwords, API keys). Never touch the live server.
- At the end of each round, add a dated entry to the Round log in `PROJECT_CONTEXT.md` (what changed, new routes/tables, how to test) and commit it.
- If a `BRIEF.md` exists in the repo root, it is the current round's task.
