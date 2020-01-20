<template>

        <v-row class="charts">

            <v-col cols="12">
                <fusioncharts
                    :type="type"
                    :width="width"
                    :height="height"
                    :dataFormat="dataFormat"
                    :dataSource="dataSource"
                ></fusioncharts>

            </v-col>

        </v-row>

</template>
<script>
    import VueFusionCharts from 'vue-fusioncharts';
    import FusionCharts from 'fusioncharts';
    import Charts from 'fusioncharts/fusioncharts.charts';
    import TimeSeries from 'fusioncharts/fusioncharts.timeseries';

    //import the theme
    import FusionTheme from 'fusioncharts/themes/fusioncharts.theme.fusion'

    import statsCount from "../../../../mixins/statsCount";


    var dataSource = {
        "chart": {
            "caption": "Channels With Most Threads Created",
            "subCaption": "Channels and their threads",
            "xAxisName": "Channels",
            "yAxisName": "Number of Threads",
            // "numberSuffix": "K",
            "theme": "umber"
        },
        "data": [
            // {
            //     "id": "2",
            //     "slug": "solutaa",
            //
            //     "label": "Venezuela",
            //     "value": "290"
            // },
            // {
            //     "id": "2",
            //     "slug": "solutaa",
            //
            //     "label": "Saudi",
            //     "value": "260"
            // },
            // {
            //     "id": "5",
            //     "slug": "solutaa",
            //
            //     "label": "Canada",
            //     "value": "180"
            // },

        ]
    };

    export default {
        mixins: [statsCount],

        data() {
            return {

                width: '100%',
                height: '600',
                type: 'column2d',
                dataFormat: 'json',
                dataSource: dataSource

            }
        },

        methods: {
            initialize () {

                var self= this

                //load countStats
                this.Load()

                axios.get('/api/channels/total/theads')
                    .then(function (response) {

                        console.log(self.dataSource.data)
                        self.dataSource.data = response.data


                       // self.$root.$emit('loading', false);

                    })
                    .catch(function (error) {

                        console.info('error');

                    })
                    .finally(function () {
                        // always executed
                    });

            },

        },
        created() {

            this.initialize()
            //console.log(this.$data)

        }
    }
</script>

<style>
    img {
        width: 30%;
        margin: auto;
        display: block;
        margin-bottom: 10px;
    }
</style>
