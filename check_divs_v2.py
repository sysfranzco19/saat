
import re

def check_divs(filepath):
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()
            
        opens = content.count('<div')
        closes = content.count('</div>')
        print(f"File: {filepath}")
        print(f"Opens: {opens}")
        print(f"Closes: {closes}")
        print(f"Net: {opens - closes}")
        
    except Exception as e:
        print(f"Error: {e}")

check_divs(r'c:\wamp64\www\saat\app\Views\backend\teacher\attendance_gamified.php')
check_divs(r'c:\wamp64\www\saat\app\Views\backend\teacher\attendance.php')
