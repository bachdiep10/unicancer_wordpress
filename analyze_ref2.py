import urllib.request
import re
import json
import sys
sys.stdout.reconfigure(encoding='utf-8')
sys.stderr.reconfigure(encoding='utf-8')

# Fetch CSS
css_url = "https://uniasiacancer.com/_astro/Layout.BJGKS_fT.css"
req = urllib.request.Request(css_url, headers={'User-Agent': 'Mozilla/5.0'})
resp = urllib.request.urlopen(req, timeout=30)
css = resp.read().decode('utf-8')
with open(r"C:\unicancer\ref-vi-layout.css", "w", encoding="utf-8") as f:
    f.write(css)
print(f"CSS saved: {len(css)} bytes")

# Fetch the main HTML
html = open(r"C:\unicancer\ref-vi-live.html", encoding="utf-8").read()

# Extract ALL sections by looking at the main element structure
# The page is built with Astro - let's find ALL major div/section elements

print("\n=== FULL DOM HIERARCHY (top-level) ===")
# Find direct children of <body>
body_match = re.search(r'<body[^>]*>(.*)', html, re.DOTALL)
if body_match:
    body = body_match.group(1)
    
    # Find all direct children tags in body
    direct_children = re.findall(r'<(\w+)[^>]*(?:class|id)="([^"]*)"[^>]*>', body[:5000])
    print("First 30 tags with class/id in body:")
    for tag, attrs in direct_children[:30]:
        print(f"  <{tag}> class/id: {attrs[:100]}")

# Extract ALL class names used in the document
all_classes = re.findall(r'class="([^"]+)"', html)
print(f"\n=== ALL UNIQUE CLASSES ({len(set(all_classes))}) ===")
for c in sorted(set(all_classes)):
    if len(c) < 150:
        print(f"  {c}")

# Find the nav structure more carefully
print("\n=== NAVIGATION STRUCTURE ===")
nav_html = re.search(r'<nav[^>]*>(.*?)</nav>', html, re.DOTALL)
if nav_html:
    nav = nav_html.group(1)
    # Get all links
    links = re.findall(r'<a[^>]+>([^<]+)</a>', nav)
    for l in links:
        l = l.strip()
        if l and len(l) > 1:
            print(f"  NAV LINK: {l}")
    # Also print raw nav for debugging
    print("\nRaw nav (first 500):", nav[:500])

# Find header structure
print("\n=== HEADER ===")
header_match = re.search(r'<header[^>]*>(.*?)</header>', html, re.DOTALL)
if header_match:
    h = header_match.group(1)
    print(h[:800])

# Find main content sections
print("\n=== MAIN CONTENT ===")
main_match = re.search(r'<main[^>]*>(.*?)</main>', html, re.DOTALL)
if main_match:
    m = main_match.group(1)
    # Count sections
    sections = re.findall(r'<(?:section|div)[^>]*class="([^"]+)"', m)
    print(f"Section/div count: {len(sections)}")
    print("First 20 class patterns:")
    for s in sections[:20]:
        print(f"  {s[:120]}")

# Check what's in the hero/carousel area
print("\n=== CAROUSEL/HERO AREA ===")
carousel = re.search(r'(?:home-carousel|carousel|hero|banner)[^"]*"[^>]*>(.*?)(?:</div>|</section>)', html, re.I | re.DOTALL)
if carousel:
    print(carousel.group(0)[:500])

# Check bg image references
print("\n=== BACKGROUND IMAGES ===")
bg_images = re.findall(r'background-image:\s*url\([\'"]?([^\'")]+)[\'"]?\)', html)
for bg in bg_images:
    print(f"  {bg}")

# Check data attributes for interactivity
print("\n=== DATA ATTRIBUTES ===")
data_attrs = re.findall(r'data-(\w+)="([^"]*)"', html)
seen_keys = set()
for key, val in data_attrs:
    if key not in seen_keys:
        seen_keys.add(key)
        print(f"  data-{key}=\"{val[:80]}\"")

# Extract the meta lang attribute
lang = re.search(r'<html[^>]+lang="([^"]+)"', html)
print(f"\n=== HTML LANG ===")
print(f"  {lang.group(1) if lang else 'not found'}")

# Font families used
print("\n=== FONT FAMILIES ===")
fonts = re.findall(r'font-family[:\s]*([^;]+)', css, re.I)
seen_fonts = set()
for f in fonts:
    f = f.strip()
    if f and f not in seen_fonts:
        seen_fonts.add(f)
        print(f"  {f}")

# Color palette
print("\n=== COLORS IN CSS ===")
colors = re.findall(r'(#[0-9a-fA-F]{3,8}|rgb\(|rgba\()', css)
seen_colors = set()
for c in colors[:100]:
    seen_colors.add(c)
for c in sorted(seen_colors):
    print(f"  {c}")

# Responsive breakpoints
print("\n=== BREAKPOINTS ===")
breakpoints = re.findall(r'@(\w+)', css)
seen_bp = set()
for bp in breakpoints:
    if bp not in seen_bp and bp not in ['keyframes', 'media', 'supports', 'layer', 'import', 'font-face', 'charset']:
        seen_bp.add(bp)
        print(f"  @{bp}")

PYEOF
