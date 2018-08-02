FROM node:9.11.1-alpine

RUN npm install -g http-server

COPY . /app
WORKDIR /app

# install project dependencies
RUN npm install

# build app for production with minification
RUN npm run build

CMD [ "http-server", "dist" ]
