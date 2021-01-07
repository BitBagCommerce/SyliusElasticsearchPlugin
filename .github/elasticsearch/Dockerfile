FROM docker:stable

RUN apk add --update bash

COPY run-elasticsearch.sh /run-elasticsearch.sh

CMD ["chmod", "g=u", "/etc/passwd"]
CMD ["chmod", "0775", "/run-elasticsearch.sh"]

ENTRYPOINT ["/run-elasticsearch.sh"]
