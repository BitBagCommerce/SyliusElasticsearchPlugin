FROM docker:stable

RUN apk add --update bash

COPY run-elasticsearch.sh /run-elasticsearch.sh

RUN chmod g=u /etc/passwd && \\
    chmod 0775 /run-elasticsearch.sh

ENTRYPOINT ["/run-elasticsearch.sh"]
