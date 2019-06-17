FROM elasticsearch:6.7.0

## 安装中文分词插件 ，不需要可以注释
RUN elasticsearch-plugin install --batch https://github.com/medcl/elasticsearch-analysis-ik/releases/download/v6.7.0/elasticsearch-analysis-ik-6.7.0.zip && \
elasticsearch-plugin install --batch https://github.com/medcl/elasticsearch-analysis-pinyin/releases/download/v6.7.0/elasticsearch-analysis-pinyin-6.7.0.zip

##必须使用elasticsearch用户来运行程序，不然会报权限错误
ENTRYPOINT su - elasticsearch -s /bin/sh -c "export PATH=$PATH:/opt/jdk-12/bin/ && /usr/share/elasticsearch/bin/elasticsearch"