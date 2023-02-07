FROM alpine:latest

RUN apk update && apk upgrade --no-cache

# beanstalkd
RUN addgroup -S beanstalkd && adduser -S -G beanstalkd beanstalkd
RUN apk add --no-cache 'su-exec>=0.2'

RUN apk --update add beanstalkd && \
    rm -rf /tmp/* && \
    rm -rf /var/cache/apk/*

RUN mkdir /data && chown beanstalkd:beanstalkd /data

VOLUME ["/data"]

EXPOSE 11300

ENTRYPOINT ["beanstalkd", "-p", "11300", "-u", "beanstalkd"]
CMD ["-b", "/data"]
