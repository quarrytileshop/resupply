# DEVELOPMENT.md

**Resupply Rocket – Development Guidelines for Grok**

Use this file as the authoritative reference when requesting code changes, new features, or bug fixes.

## MANDATORY RULES — READ FIRST
─────────────────────────────

• Fresh session only. Do NOT reference, assume, or continue from any prior chat unless the user explicitly says “continue from last chat” and pastes relevant output.  
• Ignore all xAI/Grok product/pricing/subscription/API/branding info unless asked.  
• Tools are ONLY to be used when the task explicitly requires web/X analysis, image/video viewing, or the user requests image generation/editing. No unsolicited tool use.  
• Responses must be concise and well-structured.  
  - Tables/lists/comparisons → use tables  
  - Instructions → numbered steps  
  - File changes → bullets  
• EVERY reply structure:  
  1. One-sentence prompt analysis (ambiguities, friction points, clarifications needed)  
  2. Clear headings (## Task 1: …)  
  3. Essential content only  
  4. End with “Next Steps” or “Questions” section if anything remains open  

## Code & File Change Rules
• Provide **complete file** in a markdown code block.  
• File header format:  
  `filename.php – Modified YYYY-MM-DD HH:MM – Lines: NNN`  
• Always include a bullet list of changes first:  
  - Added: …  
  - Removed: …  
  - Modified: …  
• Never partial edits or diffs — give the entire file.  
• Do **not** assume file content. If a file has not been seen in the current session, ask the user for the current full text.  
• One focused clarification question at a time.  

## User Constraints
• The owner is **not a coder**. Explain every step clearly, one at a time.  
• Provide exact cPanel / GoDaddy file paths and instructions.  
• Treat any MySQL dumps, file lists, or code the user provides as authoritative.  
• Do not begin reasoning or code generation until all required data parts are confirmed.  

## Project References
- Always read and follow the latest **README.md** (Project Goal & Must-Preserve Behaviors).  
- All code changes must preserve the core principles listed in README.md unless the user explicitly approves changes.  
- Prioritize: Mobile-first UX, instant auto-save on blur/type, clean professional HTML emails, simplicity, and security.

---

**Last updated:** 2026-05-08  
**Purpose:** Keep the GitHub repository clean and professional while giving Grok clear operating instructions.
