# Stilus

## Project setup
```
npm install
composer install
```

### Compiles and hot-reloads for development
```
# Starting up administrative panel
npm run serve
# Starting up api
composer run
```

### Compiles and minifies for production
```
npm run build
```

### Lints and fixes files
```
npm run lint
```

### Docker
To setup docker
`docker-compose up`

Running api server
`docker-compose run stilus_api composer run`

Running web server
`docker-compose run stilus_api npm run build`
