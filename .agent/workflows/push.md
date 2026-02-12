---
description: Auto-push changes to GitHub after each code modification
---

// turbo-all

After every code change, run this workflow:

1. Stage all changes
```
git add -A
```

2. Commit with a short descriptive message
```
git commit -m "<message>"
```

3. Push to origin
```
git push origin main
```