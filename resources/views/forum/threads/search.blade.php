<div class="col-sm-9 searchThreadBox">
    <search inline-template value="{{ app('request')->input('search') }}"  >

        <v-app>
            <v-form ref="entryForm" @submit.prevent="search">

                <v-card

                    class="d-flex justify-space-around"
                    color="#f8fafc"
                    flat
                    tile
                >

                    <v-text-field
                        prepend-inner-icon="search"
                        label="Search"
                        class="hidden-sm-and-down"
                        v-model="searchBox"
                        @keyup="change"
                        @submit.prevent="search"
                    >

                    </v-text-field>
                    <v-chip
                        v-if="isSearching"
                        class="ma-2"
                        {{--                                            close--}}
                        @click="clear"
                    >
                        Clear
                    </v-chip>

                </v-card>

            </v-form>

        </v-app>

    </search>
</div>
