FROM node:9.11.1-alpine

WORKDIR /app

COPY package*.json ./

# install project dependencies
RUN npm install

# build app for production with minification
RUN npm run build

CMD [ "http-server", "dist" ]
