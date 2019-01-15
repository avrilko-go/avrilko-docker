FROM elasticsearch:5.6.13

## 安装中文分词插件 ，不需要可以注释
RUN elasticsearch-plugin install https://github.com/medcl/elasticsearch-analysis-ik/releases/download/v5.6.13/elasticsearch-analysis-ik-5.6.13.zip && \
elasticsearch-plugin install https://github.com/medcl/elasticsearch-analysis-pinyin/releases/download/v5.6.13/elasticsearch-analysis-pinyin-5.6.13.zip

##必须使用elasticsearch用户来运行程序，不然会报权限错误
ENTRYPOINT su - elasticsearch -s /bin/sh -c "/usr/share/elasticsearch/bin/elasticsearch"