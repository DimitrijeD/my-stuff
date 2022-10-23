<template>
    <span>
        {{ displayTime }}
    </span>
</template>

<script>
export default{
    props: ['date'],

    data(){
        return {
            step: null,
            intervalId: null,
            timeValue: null,

            range: [
                {
                    date: "moments",
                    sufix: "ago",
                    desc: "Between now and one minute",
                    min: 0,
                    max: this.minute() - 1,
                    in: 'moments',
                },

                {
                    date: null,
                    sufix: "minutes ago",
                    desc: "Between one minute and 60 minutes",
                    min: this.minute(),
                    max: this.hour() - 1,
                    in: 'minutes',
                },

                {
                    date: null,
                    sufix: "hours ago",
                    desc: "Between 1 hour and 1 day",
                    min: this.hour(),
                    max: this.day() - 1,
                    in: 'hours',
                },

                {
                    date: null,
                    sufix: "days ago",
                    desc: "Between 1 day and 20 days",
                    min: this.day(),
                    max: this.twentyDays() - 1,
                    in: 'days',
                },

                {
                    date: null,
                    sufix: "",
                    desc: "Above 20 days just show date",
                    min: this.twentyDays(),
                    max: null,
                    in: 'dates',
                },
            ],
            

        }
    },

    created(){
        this.make()
    },

    computed: {
        displayTime(){
            clearInterval(this.intervalId)
            
            switch(this.range[this.step].in){
                case 'moments':
                    return `${this.range[this.step].date} ${this.range[this.step].sufix}`

                
                case 'minutes':
                    return `${this.getTimeValue()} ${this.range[this.step].sufix}`

                case 'hours':
                    return `${this.getTimeValue()} ${this.range[this.step].sufix}`


                case 'days':
                    return `${this.getTimeValue()} ${this.range[this.step].sufix}`

                default: // dates
                    return this.date
            } 
        }
    },

    methods: {
        make(){
            if(!this.date) return // validate date here coz recursion and prop might change, meh

            const ms = this.getMs()

            if(this.step == null){
                if(ms >= this.range[0].min && ms <= this.range[0].max){
                    this.step = 0
                } else if(ms >= this.range[1].min && ms <= this.range[1].max){
                    this.step = 1
                } else if(ms >= this.range[2].min && ms <= this.range[2].max){
                    this.step = 2
                } else if(ms >= this.range[3].min && ms <= this.range[3].max){
                    this.step = 3
                } else {
                    this.step = 4
                }
            } 

            if(this.step != 4) this.increaseStepAfter(this.range[this.step].max - ms)
        },

        getTimeValue(){
            switch(this.range[this.step].in){
                case 'moments':
                    return this.range[this.step].date
                
                case 'minutes':
                    if(this.timeValue == null) this.timeValue = this.getMinutesFromMS()

                    this.timeValue = this.getMinutesFromMS()

                    this.intervalId = setTimeout(() => {
                        this.timeValue = this.getMinutesFromMS()
                    }, this.getMStoNextMinute())

                    return this.timeValue

                case 'hours':
                    if(this.timeValue == null) this.timeValue = this.getHoursFromMS()

                    this.timeValue = this.getHoursFromMS()

                    this.intervalId = setTimeout(() => {
                        this.timeValue = this.getHoursFromMS()
                    }, this.getMStoNextHour())

                    return this.timeValue

                case 'days':
                    if(this.timeValue == null) this.timeValue = this.getDaysFromMS()

                    this.timeValue = this.getDaysFromMS()

                    this.intervalId = setTimeout(() => {
                        this.timeValue = this.getDaysFromMS()
                    }, this.getMStoNextDay())

                    return this.timeValue

                default: // dates
                    return new Date(this.date)
            }

        },

        /**
         * Recursive up to 4 currently
         * 
         * When reaches 4, it just shows date
         * 
         * @param delay time in miliseconds 
         */
        increaseStepAfter(delay){
            if(this.step == null) this.step = 0

            setTimeout(() => {
                ++this.step
                this.make()
            }, delay)
        },

        getMs(){ return Date.now() - (new Date(this.date)).getTime() },

        second()    { return 1000       }, // 1000 
        minute()    { return 60000      }, // 1000 * 60  
        hour()      { return 3600000    }, // 1000 * 60 * 60
        day()       { return 86400000   }, // 1000 * 60 * 60 * 24  
        twentyDays(){ return 1728000000 }, // 1000 * 60 * 60 * 24 * 20

        getMinutesFromMS(){ return Math.floor(this.getMs() / this.minute()) },
        getHoursFromMS()  { return Math.floor(this.getMs() / this.hour())   },
        getDaysFromMS()   { return Math.floor(this.getMs() / this.day())    },


        getMStoNextMinute(){ return this.minute() - this.getMSRemainingInMinute() + 1 },
        getMSRemainingInMinute(){ return this.getMs() % this.minute() },

        getMStoNextHour(){ return this.hour() - this.getCurrentMSHour() },
        getCurrentMSHour(){ return this.getMs() % this.hour() },

        getMStoNextDay(){ return this.day() - this.getCurrentMSDay() },
        getCurrentMSDay(){ return this.getMs() % this.day() },
    }
}
</script>