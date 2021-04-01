# Buto-Plugin-ReadmeYml

Takes yml data like /sys/mercury/readme.yml and parse it to html.
Also a save parameter to save a .md file at the same time.


## Widget parse
```
type: widget
data:
  plugin: readme/yml
  method: parse
  data:
    file: /sys/mercury/readme.yml
    save: /sys/mercury/README.md
```

Example data.
```
readme:
  name: 'My system documentation'
  date: '2021-03-28'
  description: |
    Description of my software.
  item:
    -
      name: 'Level one'
      description: ''
      item:
        -
          name: Level two
          description: |
            Some text.
            ```
            var x = 1;
            ```
          item:
            -
              name: Level three
              description: ''
```
