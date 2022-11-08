import primary from '@/Pages/Settings/Colorz/types/primary.js'
import secondary from '@/Pages/Settings/Colorz/types/secondary.js'
import ternary from '@/Pages/Settings/Colorz/types/ternary.js'

import shareableConfig from '@/Pages/Settings/Colorz/shareableConfig.js'

const defaultColorz = [primary, secondary, ternary]

for(let i in defaultColorz){
    defaultColorz[i] = {...defaultColorz[i], ...shareableConfig}
}

export default defaultColorz