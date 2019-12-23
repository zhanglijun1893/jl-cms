app = new Vue({
    el: '#app',
    data: {
        total: 0,               // 总条数
        pageSize: 10,           //每页显示多少条
        page: 1,                //当前页码
        columns: columns,       //表格头
        data: data              //表格内容
    },
    //初始化执行
    created() {},
    methods: {
        refresh(id) {        //刷新
            let page = id ? this.page : 1;
            this.fetchData(page);
        },
        changePage(index) {  //分页
            this.page = index;  // 当前页码
            this.fetchData(index);
        },
        /*op(url, title, wh) {  //添加/修改
            layerOpen(url, title, wh);
        },
        msgSuccess(msg) { //成功提示
            if(!msg) {
                msg = "操作成功";
            }
            app.$Message.success(msg);
        },
        msgError(msg) {//失败提示
            if(!msg) {
                msg = "操作失败";
            }
            app.$Message.error(msg);
        },*/
        remove(index, url, title,r) {    //删除
            if (!r) r = 0;
            let _this = this;
            this.$Modal.confirm({
                title: '确定要删除《' + title + '》吗？',
                width: 360,
                onOk: () => {
                    axios({
                        method: 'get',
                        url: url
                    }).then(function (response) {
                        if (response.data && response.data.status === 200) {
                            _this.$Message.info('删除成功');
                            _this.data.splice(index, 1);
                            if (r===1) {
                                window.location.reload();
                            }
                        } else {
                            _this.$Message.error(response.data.message);
                        }
                    }).catch(function (error) {
                        console.log(error);
                    });
                }
            })
        },
        fetchData(page) {//获取初始数据
            let self = this;
            axios({
                method: 'post',
                url: url,
                data: Qs.stringify({"page": page, "rows": rows})
            }).then(function (response) {
                if (response.data) {
                    self.total = response.data.total;
                    self.data = response.data.rows;
                }
            }).catch(function (error) {
                self.fetchError = error;
                console.log(error);
            })
        }
    }
});