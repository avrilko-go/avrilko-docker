FROM elasticsearch:5.6.13
## 安装中文分词插件 ，不需要可以注释
RUN elasticsearch-plugin install https://github.com/medcl/elasticsearch-analysis-ik/releases/download/v5.6.13/elasticsearch-analysis-ik-5.6.13.zip && \
elasticsearch-plugin install https://github.com/medcl/elasticsearch-analysis-pinyin/releases/download/v5.6.13/elasticsearch-analysis-pinyin-5.6.13.zip
ENTRYPOINT su - elasticsearch -s /bin/sh -c "/usr/share/elasticsearch/bin/elasticsearch"