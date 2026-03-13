import subprocess
import os

def run_cmd(cmd, cwd='c:\\Users\\PC\\Desktop\\Rezervist.com'):
    result = subprocess.run(cmd, shell=True, capture_output=True, text=True, cwd=cwd)
    return result.stdout.strip()

def get_files():
    output = run_cmd('git status --porcelain=v1')
    files = []
    exclude_patterns = ['git_committer.py', 'git_list.txt', 'git_status.txt', '.env', 'task.md', 'walkthrough.md', 'implementation_plan.md']
    for line in output.split('\n'):
        if not line: continue
        status = line[:2]
        path = line[3:].strip().strip('"')
        
        # Filter out my own temp files
        if any(exc in path for exc in exclude_patterns):
            continue
            
        files.append((status, path))
    return files

files = get_files()
with open('files_to_commit.txt', 'w', encoding='utf-8') as f:
    for status, path in files:
        f.write(f"{status}|{path}\n")

print(f"Found {len(files)} files to commit.")
for s, p in files[:10]:
    print(f"{s} {p}")
if len(files) > 10:
    print("...")
