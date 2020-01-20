
export default {

    data() {
        return {

            primaryButtonOverrideStyles: {
                backgroundColor: ''
            },

            linkOverrideStyles:{
              color: ''
            },

            headingOverrideStyles: {
                color: ''
            },

            paragraphOverrideStyles: {
                color: ''
            }

        }
    },

    methods: {
        initialize () {

            this.shopAppearance()

        },

        shopAppearance(){

            var self = this;

            axios.get('/api/shop/appearance')
                .then(function (response) {

                    self.primaryButtonOverrideStyles.backgroundColor = response.data.primary_button_color
                    self.linkOverrideStyles.color = response.data.link_color
                    self.headingOverrideStyles.color = response.data.heading_color
                    self.paragraphOverrideStyles.color = response.data.paragraph_color

                })
                .catch(function (error) {
                })
                .finally(function () {
                });

        }

    },
    mounted() {

        this.initialize()
    }
}
