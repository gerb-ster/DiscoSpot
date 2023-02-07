FROM debian:bullseye-slim

RUN apt-get update

# beanstalkd
RUN mkdir -p /var/lib/beanstalkd
RUN apt-get install -y beanstalkd

EXPOSE 11300
CMD ["/usr/bin/beanstalkd","-l 0.0.0.0", "-p 11300", "-b /var/lib/beanstalkd"]
