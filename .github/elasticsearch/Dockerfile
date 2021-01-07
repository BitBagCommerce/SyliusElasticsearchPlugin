FROM docker:stable

RUN apk add --update bash

COPY run-elasticsearch.sh /run-elasticsearch.sh

RUN /bin/bash chmod g=u /etc/passwd && \\
    /bin/bash chmod 0775 /run-elasticsearch.sh

ENTRYPOINT ["/run-elasticsearch.sh"]
