FROM node:lts-jod

ARG UID=1000
ARG GID=1000

RUN groupmod --gid $GID node
RUN usermod  --uid $UID node

# Fix permission error for Vite.
WORKDIR /home/node/app
RUN chown -R node /home/node/app

# Switch to the node user.
USER node

# Install dependencies a level up from the app, node traverses upwards to find node modules.
WORKDIR /home/node
COPY --chown=node:node package.json package-lock.json ./
RUN npm install

# Ensure paths are set correctly so executables can be used.
ENV NODE_PATH /home/node/node_modules
ENV PATH /home/node/node_modules/.bin:$PATH

# Copy app into the app directory.
WORKDIR /home/node/app
COPY --chown=node:node . .

# Expose port and run the dev command.
EXPOSE 8080
CMD ["npm", "run", "dev"]

