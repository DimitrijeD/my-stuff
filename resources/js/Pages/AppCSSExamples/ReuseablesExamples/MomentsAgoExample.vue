<template>
    <div class="space-y-10">
        <h1>MomentsAgo Examples</h1>
        <h2>Well if something breaks here , gl</h2>
        <div class="space-y-4 w-[90%]">
            <div v-for="date in datesMS" class="text-gray-200 p-2 m-2 bg-darker-50 flex flex-row space-x-8 ">
                <MomentsAgo :date="date.date" />
                <p>{{ date.forHuman }}</p>
                <p>{{ date.date }}</p>
            </div>
        </div>
</div>
</template>

<script>
import MomentsAgo from '@/Components/MomentsAgo/MomentsAgo.vue'

export default {
    components: { MomentsAgo, },

    data(){
        return {
            someDate: '2022-10-14T13:35:39.000000Z',
            datesMS: [],
        }
    },

    created(){
        const now = Date.now()

        this.datesMS = [
            {
                ms: now,
                forHuman: 'Now'
            },
            {
                ms: now - this.second() * 3,
                forHuman: '3 Seconds Ago'
            },
            {
                ms: now - this.second() * 30,
                forHuman: '30 Seconds Ago'
            },
            {
                ms: now - this.second() * 50,
                forHuman: '50 Seconds Ago'
            },
            {
                ms: now - this.minute() - this.second(),
                forHuman: '1 Minute and 1 Second Ago'
            },
            {
                ms: now - this.minute() - this.second() * 33,
                forHuman: '1 Minute and 33 Seconds Ago'
            },
            {
                ms: now - this.minute() - this.second() * 51,
                forHuman: '1 Minute and 51 Seconds Ago'
            },
            {
                ms: now - this.minute() * 5,
                forHuman: '5 Minutes Ago'
            },
            {
                ms: now - this.minute() * 40,
                forHuman: '40 Minutes Ago'
            },
            {
                ms: now - this.minute() * 59 - this.second() * 55,
                forHuman: '59 Minutes And 55 Seconds Ago'
            },
            {
                ms: now - this.hour(),
                forHuman: '1 Hour Ago'
            },

            {
                ms: now - this.hour() - this.minute() - this.second(),
                forHuman: '1 Hour, One Minute and One Second Ago'
            },
            {
                ms: now - this.hour() * 6,
                forHuman: '6 Hours Ago'
            },
            {
                ms: now - this.hour() * 23 - this.minute() * 59 - this.second() * 55,
                forHuman: '23 Hours, 59 Minute and 55 Seconds Ago'
            },
            {
                ms: now - this.day(),
                forHuman: 'Day Ago'
            },
            {
                ms: now - this.day() * 5,
                forHuman: '5 Days Ago'
            },
            {
                ms: now - this.day() * 19 - this.hour() * 23 - this.minute() * 59 - this.second() * 55,
                forHuman: '19 Days, 23 Hours, 59 Minutes and 55 Seconds Ago'
            },
            {
                ms: now - this.day() * 40,
                forHuman: '40 Days Ago'
            },
        ]

        for(let i in this.datesMS){
            this.datesMS[i].date = new Date(this.datesMS[i].ms) 
        }
    },

    methods: {
        second()    { return 1000       }, // 1000 
        minute()    { return 60000      }, // 1000 * 60  
        hour()      { return 3600000    }, // 1000 * 60 * 60
        day()       { return 86400000   }, // 1000 * 60 * 60 * 24  
        twentyDays(){ return 1728000000 }, // 1000 * 60 * 60 * 24 * 20
    }
}

</script>