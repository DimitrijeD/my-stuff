<template>
    {{ displayTime }}
</template>

<script>
export default{
    props: ['date'],

    data(){
        return {
            step: null,
            timeValue: null,
            valueIntervalId: null,

            range: [
                {
                    date: "moments",
                    sufix: "ago",
                    min: 0,
                    max: this.minute() - 1,
                    in: 'moments',
                },

                {
                    date: null,
                    sufix: "minutes ago",
                    min: this.minute(),
                    max: this.hour() - 1,
                    in: 'minutes',
                },

                {
                    date: null,
                    sufix: "hours ago",
                    min: this.hour(),
                    max: this.day() - 1,
                    in: 'hours',
                },

                {
                    date: null,
                    sufix: "days ago",
                    min: this.day(),
                    max: this.twentyDays() - 1,
                    in: 'days',
                },

                {
                    date: null,
                    sufix: "",
                    min: this.twentyDays(),
                    max: true,
                    in: 'dates',
                },
            ],
            

        }
    },

    watch: {
        /**
         * If 'date' prop never changes, this watcher can be removed.
         */
        date(){
            clearInterval(this.valueIntervalId)

            this.step = null
            this.timeValue = null
            this.valueIntervalId = null

            this.make()
        }
    },

    created(){
        this.make()
    },

    computed: {
        displayTime(){
            clearInterval(this.valueIntervalId)

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
                    return this.staticDateTimeDisplay()
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
                    return this.activateTimeout(this.minute())

                case 'hours':
                    return this.activateTimeout(this.hour())

                case 'days':
                    return this.activateTimeout(this.day())

                /**
                 * Here you can format date time display and no more changes to it will be applyed
                 */
                default: 
                    return this.date
            }

        },

        staticDateTimeDisplay(){
            return new Date(this.date)
        },

        activateTimeout(interval){
            this.timeValue = this.getValueFromMS(interval)

            this.valueIntervalId = setTimeout(() => {
                this.timeValue = this.getValueFromMS(interval)
            }, this.getRemainingMStoNextValue(interval))

            return this.timeValue
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

        /**
         * Returns miliseconds up to @prop date
         */
        getMs(){ return Date.now() - (new Date(this.date)).getTime() },

        second()    { return 1000       }, // 1000 
        minute()    { return 60000      }, // 1000 * 60  
        hour()      { return 3600000    }, // 1000 * 60 * 60
        day()       { return 86400000   }, // 1000 * 60 * 60 * 24  
        twentyDays(){ return 1728000000 }, // 1000 * 60 * 60 * 24 * 20

        /**
         * Returns current value of provided interval
         * 
         * Example:
         *      1)
         *      interval = 2 * hour() + 31 * minute()
         *      return 2 
         * 
         *      2)
         *      interval = 31 * minute() + 10 *second()
         *      return 31
         * 
         * @param {Number} interval ( second(), minute(), hour(), day() or twentyDays() )
         */
        getValueFromMS(interval) { return Math.floor(this.getMs() / interval) },

        /**
         * Returns miliseconds required to reach next value
         * 
         * Example:
         *      interval = minute()
         *      (getMs() % interval ) = second() * 33
         *      
         *      return 1 minute - 33 seconds = 27 seconds = 27 000 miliseonds
         * 
         * @param {Number} interval  
         */
        getRemainingMStoNextValue(interval){ return interval - this.getMs() % interval  },
    }
}
</script>