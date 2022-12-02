export class Resizer {
    /**
     * maintainOffsetTop - how much is element allowed to grow to/from top
     * heightGrowthBy    - growth factor for height, how much to grow relative to size of screen
     * widthGrowthBy     - growth factor for width, how much to grow relative to size of screen
     * minWidth          - minimum desired width for element
     * minHeight         - minimum desired height for element
     * onResize          - classes to apply when element is resized
     * onFullScreen      - classes to apply when element is forced to go full screen
     * element           - DOM element which is being resized
     * tryOffsetLeft     - try to make element grow from this offset
     * dontApplyClasses  - boolean which determines if computed classes should be added to this.element 
     *      (case with chat windows - <Chat> component creates size and passes that value to every <ChatWindow>)
     */
    constructor({ maintainOffsetTop, heightGrowthBy, widthGrowthBy, minWidth, minHeight, onResize, onFullScreen, dontApplyClasses }){
        this.maintainOffsetTop = maintainOffsetTop != undefined ? maintainOffsetTop : 0
        this.heightGrowthBy    = heightGrowthBy    != undefined ? heightGrowthBy    : 0
        this.widthGrowthBy     = widthGrowthBy     != undefined ? widthGrowthBy     : 0
 
        this.minWidth          = minWidth          != undefined ? minWidth          : 0
        this.minHeight         = minHeight         != undefined ? minHeight         : 0
 
        this.onResize          = onResize          != undefined ? onResize          : {}
        this.onFullScreen      = onFullScreen      != undefined ? onFullScreen      : {}
        this.dontApplyClasses  = dontApplyClasses  != undefined ? dontApplyClasses  : false

        this.fromSetter()
        this.setupComputed()
    }

    /**
     * Properties which are created withing class
     */
    setupComputed(){
        this.targetWidth = null
        this.targetHeight = null
        this.debounceResize = true
        this.mergedClasses = {}

        this.isFullScreen = null
    }

    /**
     * Properties passed via global setter just before run() method is called because some values are relative and depend on current state of component
     */
    fromSetter(){
        this.element = null 
        this.tryOffsetLeft = 0 
    }

    setTargetWidth(){
        this.targetWidth = Math.round( this.minWidth  + this.getWidthGrowth() )
    }

    setTargetHeight(){
        this.targetHeight = Math.round( this.minHeight  + this.getHeightGrowth() )
    }

    setTryOffsetLeft(num = 0){
        this.tryOffsetLeft = num 
    }

    setElement(el){
        this.element = el 
    }

    getElementLeftOffset(){
        return this.element.getBoundingClientRect().left
    }

    getElementTopOffset(){
        return this.element.getBoundingClientRect().top
    }

    getWidthGrowth(){
        return window.innerWidth * this.widthGrowthBy.width + window.innerHeight * this.widthGrowthBy.height
    }

    getHeightGrowth(){
        return window.innerWidth * this.heightGrowthBy.width + window.innerHeight * this.heightGrowthBy.height
    }

    run(){
        if(!this.debounceResize) return false
        this.allowResizeAfter()

        this.setTargetWidth()
        this.setTargetHeight()

        this.shouldFullScreen()
            ? this.makeFullScreen()
            : this.updateClasses()

        if(!this.dontApplyClasses) this.putClassesInElement(this.mergedClasses)

        return {
            isFullScreen: this.isFullScreen,
            classes: this.mergedClasses
        }
    }

    isWidthOverflowing(){
        return window.innerWidth < this.targetWidth + this.tryOffsetLeft
    }

    isHeightOverflowing(){
        return window.innerHeight < this.targetHeight + this.maintainOffsetTop
    }

    shouldFullScreen(){
        if(this.isWidthOverflowing()) return true
        if(this.isHeightOverflowing()) return true

        return false
    }

    allowResizeAfter(){
        this.debounceResize = false

        setTimeout(() => {
            this.debounceResize = true
        }, 50);
    }

    updateClasses(left){
        this.isFullScreen = false

        this.prepareClasses( {
                height: `${this.targetHeight}px`,
                width:  `${this.targetWidth}px`,
            }, this.onResize 
        )
    }

    makeFullScreen(){
        this.isFullScreen = true

        this.prepareClasses( {
                height: `${window.innerHeight - this.maintainOffsetTop}px`,
                width:  `${this.targetWidth}px`,
            }, this.onFullScreen 
        )
    }

    putClassesInElement(classes){
        for(let property in classes){
            this.element.style[property] = classes[property]
        } 
    }

    getComputedClasses(){
        return {
            height: `${this.targetHeight}px`,
            width:  `${this.targetWidth}px`,
        }
    }

    prepareClasses(computedClasses, staticClasses){
        this.mergedClasses = { ...computedClasses, ...staticClasses }
    }
}