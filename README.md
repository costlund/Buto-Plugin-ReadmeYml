# Buto-Plugin-ReadmeYml

Takes yml data like /sys/mercury/readme.yml and parse it to html.
Also a save parameter to save a .md file at the same time.

```
type: widget
data:
  plugin: readme/yml
  method: parse
  data:
    file: /sys/mercury/readme.yml
    save: /sys/mercury/README.md
```
