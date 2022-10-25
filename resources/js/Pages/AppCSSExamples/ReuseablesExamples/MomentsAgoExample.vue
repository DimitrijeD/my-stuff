<template>
    <div class="bg ">
        <h1>MomentsAgo playground</h1>
        <p>
            This component serves to document how MomentsAgo works and to check if it is updating in correct relative moments. 
            Expected outcome is:some older date then now (or equal to now) gets converted into miliseconds and MomentsAgo component, on create hook, calculates what it should display (e.g.
            few moments ago, 2 minutes ago, 6 days ago...). 
            Watcher is set on your date prop to refresh on value change. You can delete it if dates never change.
        </p>
        <p> Focus is on newer events which passed few seconds, minutes.. days ago. So datetime you provide should have exact time stamp with h/m/s .</p>

        <p><span class="danger">If it doesn't update in that time, there is a bug and you shoudn't use this component (or u can fix it xd )</span></p>

        <div>
            <h2>Use</h2>

            <pre><code> import MomentsAgo from '../where you put this component is up to you';  </code></pre>
            <pre><code>const date = 'Mon Oct 24 2022 22:30:58 GMT+0200 (Central European Summer Time)'</code></pre>
            <pre><code> &lt;MomentsAgo :date="date"/&gt; </code></pre>
        </div>


        <div>
            <h2>Dependencies</h2>
            <p>None. Works in VueJs 3. Should work in 2 as well (haven't tested it)</p>
        </div>

        <div>
            <h2>Table of examples</h2>
            <p>Second column is static text which shows when will component update it's value from refresh moment. Hit Reset button to see results.</p>

            <div class="table-btn-wrap">
                <table>
                    <tr>
                        <th>MomentsAgo Component Output</th>
                        <th>When will output update</th>
                        <th>Raw date</th>
                    </tr>
                    <tr v-for="date in datesMS" class="">
                        <td><MomentsAgo :date="date.date" /></td>
                        <td>{{ date.forHuman }}</td>
                        <td>{{ date.date }}</td>
                    </tr>
                </table>
                <button class="updateall" @click="updateDate">Reset all dates to now</button>
            </div>
        </div>


        <div>
            <h2>More</h2>
            <h3>Timeline</h3>
            <p>
                You pass date as prop. Code determines in which range that moment in time falls in  e.g. date which is <code>6 minutes 20 seconds ago</code> from now will fall in 'minutes' range.
                These ranges are determines within <code>range</code> object of MomentsAgo component (<code>range[int].in</code> full path to that key).
                Then number of miliseconds to next minute  <i>(in our example it is <code>6m 20s</code> to <code>7</code>)</i> is calculated <i>(which is 40 seconds)</i> and timeout event is set to
                occur in that amount of miliseconds which will increase time display by <code>1</code>. If time value is greater then it's interval step will increase e.g. <code>59 minutes</code> will go to <code>1 hour</code>, <code>23 hours</code> will go to <code>1 day ago</code> etc. 
            </p>

            <p>
                Once <code>range</code> is exhausted <i>(currently that will happend if date is older then 20 days)</i> raw date time value you passed as prop will display. You can modify it's 
                display value by modifying function <code>staticDateTimeDisplay()</code>.
            </p>

            <h3>Language</h3>
            <p>Supports only English prefix and suffix, but u can change these strings in <code>range[int].prefix</code> and <code>range[int].sufix</code></p>

            <h3>Acceptable DateTime formats</h3>
            <p>
                Any date time format that gets converted to miliseconds by following code <br>
                <code>(new Date(date)).getTime()</code> <br>
                is a valid date format. <br>
            </p>

            <table style="margin-top: 5px">
                <tr>
                    <td>MomentsAgo outputs</td>
                    <td>Date Formats</td>
                </tr>

                <tr v-for="format in exampleDateFormats">
                    <td><MomentsAgo :date="format" /></td>
                    <td><code>{{ format }}</code></td>
                </tr>
            </table>

        </div>
    </div>
</template>

<script>
import MomentsAgo from '@/Components/MomentsAgo/MomentsAgo.vue'

export default {
    components: { MomentsAgo, },

    data(){
        return {
            datesMS: [],
            now: Date.now(),

            exampleDateFormats: [
                new Date(), 
                new Date().toUTCString(),
                (new Date()).toISOString().slice(0, 19).replace(/-/g, "/").replace("T", " "),
            ],

        }
    },

    created(){
        this.makeAll()
    },

    methods: {
        second()    { return 1000       }, // 1000 
        minute()    { return 60000      }, // 1000 * 60  
        hour()      { return 3600000    }, // 1000 * 60 * 60
        day()       { return 86400000   }, // 1000 * 60 * 60 * 24  
        twentyDays(){ return 1728000000 }, // 1000 * 60 * 60 * 24 * 20

        updateDate() {
            this.makeAll()
        },

        makeAll(){
            this.now = Date.now()

            this.datesMS = [
                {
                    ms: this.now - this.second() * 57,
                    forHuman: `in about 3 seconds to 1 minutes ago`
                },
                {
                    ms: this.now - this.second() * 3,
                    forHuman: `in about 57 seconds to 1 minutes ago`
                },
                {
                    ms: this.now - this.second() * 50,
                    forHuman: `in about 10 seconds to 1 minutes ago`
                },
                {
                    ms: this.now - this.minute() - this.second() * 33,
                    forHuman: `in about 27 seconds to 2 minutes ago`
                },
                {
                    ms: this.now - this.minute() - this.second() * 51,
                    forHuman: `in about 9 seconds to 2 minutes ago`
                },
                {
                    ms: this.now - this.minute() * 5,
                    forHuman: `in about 60 seconds to 6 minutes ago`
                },
                {
                    ms: this.now - this.minute() * 40,
                    forHuman: `in about 27 seconds to 41 minutes ago`
                },
                {
                    ms: this.now - this.minute() * 59 - this.second() * 55,
                    forHuman: `in about 5 seconds to 1 hours ago`
                },
                {
                    ms: this.now - this.hour(),
                    forHuman: `in about 1 hour to 2 hours ago`
                },
                {
                    ms: this.now - this.hour() * 6,
                    forHuman: `in about 1 hour to 7 hours ago`
                },
                {
                    ms: this.now - this.hour() * 23 - this.minute() * 59 - this.second() * 55,
                    forHuman: `in about 5 seconds to 1 days ago`
                },
                {
                    ms: this.now - this.day(),
                    forHuman: `in about 1 day to 2 days ago`
                },
                {
                    ms: this.now - this.day() * 19 - this.hour() * 23 - this.minute() * 59 - this.second() * 55,
                    forHuman: `in about 5 seconds to raw date; no more updates`
                },
            ]

            for(let i in this.datesMS){
                this.datesMS[i].date = new Date(this.datesMS[i].ms) 
            }
        },

    }
}

</script>

<style scoped>
    table {
        border-style: solid;
        border-color: #0f0f0f;
        table-layout: fixed;
    }

    .table-btn-wrap {
        display: flex;
        column-gap: 1.6rem;
        margin-bottom: 3rem;
    }

    p {
        margin: 1rem;
    }

    pre{
        margin: 2rem;
    }

    h1 {
        color: #488cbd;
        margin-bottom: 3rem;
    }

    h2 {
        color: #366a8f;
        margin-bottom: 1.5rem;
    }

    .bg {
        background-color: black;
        padding: 2rem 2rem 14rem 2rem;
        color: #c2c0ba;
        line-height: 1.5rem;
    }

    td {
        padding: 1.5rem;
        width:33%;
    }

    tr:nth-child(odd) {
        background-color: #0d0d0d;
    }

    .updateall{
        height: full;
        width: full;
        font-size: 2rem;
        background-color: #1e364a;
        padding: 2px;
        color: #c2c0ba;


    }

    code {
        background-color: #0f0f0f;
        padding: 0.5rem;
    }

    .danger{
        color: red;
    }

</style>