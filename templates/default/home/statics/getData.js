app = new Vue({
    el: '#app',
    data: {
        category: [],
        config: [],
    },
    created() {
        let self = this;
        this.fetchData("{:url('/api/index/getCategory')}","POST")
            .then(function (res) {
                self.category = res;
            });
    },
    methods: {
        fetchData(url, method) {
            let self = this;
            return new Promise((resolve, reject) => {
                axios({
                    method: method,
                    url: url,
                }).then(function (response) {
                    if (response.data && response.data.status === 200) {
                        resolve(response.data.data)
                    }
                }).catch(function (error) {
                    //console.log(error);
                    reject(error)
                });
            });

        }
    }
});
function shMenu() {
    let arr = document.getElementsByClassName("header-wrapper");
    let m = document.getElementsByClassName("menu");
    let className = "open";
    let reg = new RegExp('(^|\\s)' + className + '(\\s|$)');
    if (reg.test(arr[0].className)) {
        arr[0].classList.remove("open");
        m[0].style.height = 0;
    } else {
        arr[0].classList.add("open");
        m[0].style.height = "100%";
    }
}