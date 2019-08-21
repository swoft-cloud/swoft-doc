# 基于Hugo 的全新的官网

预览地址：[https://swoft.netlify.com/](https://swoft.netlify.com/)

此分支将用来构建全新的 `Swoft` 文档，使用基于 [Hugo](https://sourcethemes.com/academic/#expo) 的模板 [Academic Kickstart](https://sourcethemes.com/academic/)来构建，并通过 [Netlify](https://netlify.com) 进行持续部署。

## 本地开发

首先需要安装 Hugo 根据 Hugo 官网文档 [Install Hugo](https://gohugo.io/getting-started/installing/) 的介绍安装 Hugo。

本站使用的是 [Academic Kickstart](https://sourcethemes.com/academic/) 主题进行构建，请先阅读学习此主题文档。 

之后克隆项目，使用 Hugo 运行本地服务

```bash
# 克隆项目
$ git clone https://github.com/swoft-cloud/swoft-doc.git
# 切换目录
$ cd swoft-doc
# 切换到 hugo-2.x 分支
$ git checkout hugo-2.x
# 下载 Hugo 的主题
$ git submodule update --init --recursive
# 启动本地服务
$ hugo server
```
## 如何参与贡献

目前网站处于开发状态，欢迎大家：

* 参与网站样式开发
* 参与文档内容编写
* 修复错别字或错误的书写格式
* 发 issue 讨论译法或书写格式
* 发 issue 讨论相关建议或问题
* 参与英文文档翻译。

注意：

原则上这里只进行文档官网的建设工作，此处不适合讨论 Swoft 的相关问题，建议相关问题在 Swoft 的相关仓库 、聊天室 或各大主流技术社区讨论，以便得到更多人的帮助和更充分的讨论。