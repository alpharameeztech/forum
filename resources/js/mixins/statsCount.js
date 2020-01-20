
export default {

    data() {
        return {
            countMembers: null,
            countThreads: null,
            countReplies: null,
            countChannels: null
        }
    },

    methods: {
        Load () {

            this.CountMembers()

            this.CountThreads()

            this.CountReplies()

            this.CountChannels()


        },

        CountMembers() {

            var self = this;

            axios.get('/api/members/count')
                .then(function (response) {
                    self.countMembers = response.data
                })
                .catch(function (error) {
                })
                .finally(function () {
                });

        },

        CountThreads(){
            var self = this;

            axios.get('/api/threads/count')
                .then(function (response) {
                    self.countThreads = response.data
                })
                .catch(function (error) {
                })
                .finally(function () {
                });
        },

        CountReplies(){

            var self = this;

            axios.get('/api/replies/count')
                .then(function (response) {
                    self.countReplies = response.data
                })
                .catch(function (error) {
                })
                .finally(function () {
                });
        },

        CountChannels(){

            var self = this;

            axios.get('/api/channels/count')
                .then(function (response) {
                    self.countChannels = response.data
                })
                .catch(function (error) {
                })
                .finally(function () {
                });

        }

    },
    mounted() {

        this.Load()
    }
}
