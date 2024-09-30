#!/usr/bin/env python3

import os
import subprocess
from datetime import datetime, timedelta

# Configuration
STORAGE_DIR="./storage"
COMMIT_FILE=os.path.join(STORAGE_DIR, "commit.txt")

# Ensure storage directory exists
os.makedirs(STORAGE_DIR, exist_ok=True)

def run_command(cmd):
    """Runs a shell command and captures output."""
    result = subprocess.run(cmd, shell=True, capture_output=True, text=True)
    if result.returncode != 0:
        print(f"Error: {result.stderr.strip()}")
    return result.stdout.strip()

def create_commit(commit_date):
    """Creates a backdated commit with the given date."""
    formatted_date = commit_date.strftime("%Y-%m-%d %H:%M:%S")

    # Write commit content
    with open(COMMIT_FILE, "w") as f:
        f.write(f"Backdated commit on {formatted_date}\n")

    # Git commands to add, commit with backdated timestamps
    run_command(f'git add {COMMIT_FILE}')
    run_command(f'GIT_COMMITTER_DATE="{formatted_date}" git commit --date="{formatted_date}" -m "Backdated commit on {formatted_date}"')

    print(f"✔ Committed: {formatted_date}")

def generate_commits(start_date, end_date, interval):
    """Generates commits from start_date to end_date with given interval."""
    current_date = start_date

    while current_date <= end_date:
        create_commit(current_date)
        current_date += interval

    print("\n✅ All commits created. Run 'git log --pretty=fuller' to verify.")

if __name__ == "__main__":
    import sys

    if len(sys.argv) != 4:
        print("Usage: python git_past_commits.py <start_date> <end_date> <interval>")
        print("Example: python git_past_commits.py '2024-01-01' '2024-01-10' '1d'")
        print("Example: python git_past_commits.py '2024-01-01 10:00' '2024-01-01 18:00' '2h'")
        sys.exit(1)

    # Parse arguments
    start_date = datetime.strptime(sys.argv[1], "%Y-%m-%d %H:%M")
    end_date = datetime.strptime(sys.argv[2], "%Y-%m-%d %H:%M")
    interval_str = sys.argv[3]

    # Convert interval to timedelta
    if "d" in interval_str:
        interval = timedelta(days=int(interval_str.replace("d", "")))
    elif "h" in interval_str:
        interval = timedelta(hours=int(interval_str.replace("h", "")))
    elif "m" in interval_str:
        interval = timedelta(minutes=int(interval_str.replace("m", "")))
    else:
        print("Invalid interval format. Use 'Xd' for days, 'Xh' for hours, 'Xm' for minutes.")
        sys.exit(1)

    # Run commit generator
    generate_commits(start_date, end_date, interval)