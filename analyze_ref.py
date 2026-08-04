import urllib.request
import re
import json

import sys
sys.stdout.reconfigure(encoding='utf-8')
sys.stderr.reconfigure(encoding='utf-8')

url = "https://uniasiacancer.com/vi"
req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0', 'Accept-Encoding': 'identity'})
resp = urllib.request.urlopen(req, timeout=30)
raw = resp.read()
try:
    html = raw.decode('utf-8')
except:
    try:
        html = raw.decode('gbk')
    except:
        html = raw.decode('latin-1')

with open(r"C:\unicancer\ref-vi-live.html", "w", encoding="utf-8") as f:
    f.write(html)

print(f"Saved {len(html)} bytes")

# Extract title
title = re.search(r'<title>([^<]+)</title>', html)
if title:
    print(f"\n=== TITLE ===")
    print(f"  {title.group(1)}")

# Extract CSS links
css_links = re.findall(r'href="([^"]+\.css[^"]*)"', html)
print(f"\n=== CSS LINKS ({len(css_links)}) ===")
for c in css_links[:15]:
    print(f"  {c}")

# Extract JS links
js_links = re.findall(r'src="([^"]+\.js[^"]*)"', html)
print(f"\n=== JS LINKS ({len(js_links)}) ===")
for j in js_links[:15]:
    print(f"  {j}")

# Extract section IDs
section_ids = re.findall(r'id="([^"]+)"', html)
print(f"\n=== SECTION IDs ({len(section_ids)}) ===")
for s in section_ids[:25]:
    print(f"  #{s}")

# Extract nav menu text
nav_matches = re.findall(r'<nav[^>]*>(.*?)</nav>', html, re.DOTALL)
print(f"\n=== NAV ({len(nav_matches)}) ===")
for nav in nav_matches:
    links = re.findall(r'<a[^>]*href="([^"]*)"[^>]*>([^<]*)</a>', nav)
    for href, text in links[:15]:
        text = text.strip()
        if text:
            print(f"  [{text}] -> {href}")

# Extract key classes
key_classes = re.findall(r'class="([^"]*)"', html)
important = [c for c in key_classes if any(k in c.lower() for k in ['section', 'hero', 'intro', 'about', 'doctor', 'cancer', 'treatment', 'service', 'story', 'faq', 'contact', 'footer', 'nav', 'header', 'btn', 'card', 'grid', 'container', 'swiper', 'slide', 'banner'])]
print(f"\n=== KEY CLASSES (filtered, {len(important)}) ===")
seen = set()
for c in important:
    if c not in seen and len(c) < 200:
        seen.add(c)
        print(f"  .{c}")

# Extract body text content (first meaningful chunks)
body_match = re.search(r'<body[^>]*>(.*?)</body>', html, re.DOTALL)
if body_match:
    body_text = re.sub(r'<script[^>]*>.*?</script>', '', body_match.group(1), flags=re.DOTALL)
    body_text = re.sub(r'<style[^>]*>.*?</style>', '', body_text, flags=re.DOTALL)
    body_text = re.sub(r'<[^>]+>', ' ', body_text)
    body_text = re.sub(r'\s+', ' ', body_text).strip()
    print(f"\n=== BODY TEXT PREVIEW (first 1000 chars) ===")
    print(body_text[:1000])

# Extract Astro island attributes
astro = re.findall(r'astro-[\w-]+', html)
print(f"\n=== ASTRO ATTRIBUTES ({len(set(astro))}) ===")
for a in sorted(set(astro))[:20]:
    print(f"  {a}")

# Check for tailwind CDN script
tailwind_cdn = re.search(r'<script[^>]+tailwindcss[^>]*>', html)
if tailwind_cdn:
    print(f"\n=== TAILWIND CDN ===")
    print(tailwind_cdn.group(0)[:300])

# Inline styles
inline_styles = re.findall(r'style="([^"]+)"', html)
print(f"\n=== INLINE STYLES ({len(inline_styles)}) ===")
seen_styles = set()
for s in inline_styles:
    if s not in seen_styles and len(s) > 3:
        seen_styles.add(s)
        print(f"  {s}")

# Meta descriptions
meta_desc = re.search(r'<meta name="description" content="([^"]+)"', html)
if meta_desc:
    print(f"\n=== META DESCRIPTION ===")
    print(f"  {meta_desc.group(1)}")

# Image sources
imgs = re.findall(r'<img[^>]+src="([^"]+)"', html)
print(f"\n=== IMAGES ({len(imgs)}) ===")
for img in imgs[:10]:
    print(f"  {img}")

# Hero/content sections in order
print(f"\n=== PAGE SECTIONS (top-level children of main/body) ===")
main_match = re.search(r'<main[^>]*>(.*?)</main>', html, re.DOTALL)
if main_match:
    sections = re.findall(r'<(?:section|div)[^>]*(?:class|id)="([^"]*(?:hero|intro|about|doctor|cancer|treatment|service|story|faq|contact|banner|feature|why|choose|patient|testimonial|news|blog|cta|action|info)[^"]*)"[^>]*>', main_match.group(1), re.I)
    for s in sections[:20]:
        print(f"  {s}")
