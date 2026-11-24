# Taste

- Prefers refactors/conversions that remove legacy code outright rather than keeping backward-compatibility shims or leftover dead code ("without backward compat, legacy nor leftovers"). Confidence: 0.6
- Wants snappy, client-side interactivity (Alpine.js) for UI state instead of waiting on server round-trips ("use alpine for better and snappy interactivity"). Expects derived/live UI (e.g. a summary panel) to update on every keystroke/field change, and treats deferred server-round-trip updates as a defect ("a lot of the form field changes doesnt effect the live brief"). Confidence: 0.75
- Expects UI changes to be proven in a real browser (Chrome/CDP or browser MCP) against the live page, not just via PHP tests and grep of static markup ("you should try and prove yourself with chrome mcp"). Confidence: 0.6
