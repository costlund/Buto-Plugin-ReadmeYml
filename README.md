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

## Example
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
            var x = 1;
          item:
            -
              name: Level three
              description: ''
```

## Example - Link
One could add link between elements.
```
readme:
  name: 'My system documentation'
  date: '2021-03-28'
  description: |
    Description of my software.
  item:
    -
      name: 'Level one'
      link: level_two
      description: 'Read more in Level two.'
    -
      name: 'Level two'
      anchor: level_two
```

## Development
To help development of a readme one could use parameter description_dev.
This will add extra text to description (not in md file).
```
readme:
  name: 'My system documentation'
  description: |
    Description of my software.
  description_dev: |
    Description of my application.
```
