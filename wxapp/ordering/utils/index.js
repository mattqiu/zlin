/**
 * 封装的常用方法
 */
class common {
  //判断空值，包括{}和[]，空为true，否则为false
  judgeNull(value) {
    if (value == null || value == undefined) return true
    if (this.judgeString(value)) {
      if (value.trim().length == 0) return true
    } else if (this.judgeArray(value)) {
      if (value.length == 0) return true
    } else if (this.judgeObject(value)) {
      for (let name in value) return false
      return true
    }
    return false;
  }
  //判断是否为字符串类型，是为true，否则为false
  judgeString(value) {
    return value != null && value != undefined && value.constructor == String
  }
  //判断是否为数字类型，是为true，否则为false
  judgeNumber(value) {
    return value != null && value != undefined && value.constructor == Number
  }
  //判断是否为布尔类型，是为true，否则为false
  judgeBoolean(value) {
    return value != null && value != undefined && value.constructor == Boolean
  }
  //判断是否为数组类型，是为true，否则为false
  judgeArray(value) {
    return value != null && value != undefined && value.constructor == Array
  }
  //判断是否为对象类型，是为true，否则为false
  judgeObject(value) {
    return value != null && value != undefined && value.constructor == Object
  }
  //判断是否为方法类型，是为true，否则为false
  judgeFunction(value) {
    return value != null && value != undefined && value.constructor == Function
  }
  //合并对象，深层克隆
  mergeObject() {
    let newObject = {}
    for (let a = 0; a < arguments.length; a++) {
      let mergeObject = arguments[a]
      for (let prototype in mergeObject) {
        let mergeObjectPrototype = mergeObject[prototype]
        if (this.judgeObject(mergeObjectPrototype)) {
          newObject[prototype] = this.mergeObject({}, mergeObjectPrototype)
        } else if (this.judgeArray(mergeObjectPrototype) && this.judgeObject(mergeObjectPrototype[0])) {
          let newArray = []
          for (let b = 0; b < mergeObjectPrototype.length; b++) {
            newArray.push(this.mergeObject({}, mergeObjectPrototype[a]))
          }
          newObject[prototype] = newArray
        } else {
          newObject[prototype] = mergeObjectPrototype
        }
      }
    }
    return newObject
  }
  //同微信官方getApp
  getApp() {
    return getApp()
  }
  //同微信官方getCurrentPages
  getCurrentPages() {
    return getCurrentPages()
  }
  //获取当前页
  getCurrentPage() {
    let pages = this.getCurrentPages()
    return pages[pages.length - 1]
  }
  //获取当前页路径
  getCurrentPath() {
    return this.getCurrentPage().__route__
  }
  //getRelativePath的简易封装，不需要当前路径，只需要目标路径
  getPath(targetPath) {
    let currentPath = this.getCurrentPath()
    return this.getRelativePath(currentPath, targetPath)
  }
  //获取两个路径之间相对路径
  getRelativePath(currentPath, targetPath) {
    let currentPathArray = currentPath.split('/')
    let targetPathArray = targetPath.split('/')
    let samePath = false
    let levelNumber = 0
    let relativePath = ''
    for (let a = 0; a < currentPathArray.length; a++) {
      let currentPathData = currentPathArray[a]
      for (let b = 0; b < targetPathArray.length; b++) {
        let targetPathData = targetPathArray[b]
        if (targetPathData == currentPathData) {
          levelNumber = currentPathArray.length - b - 1
          samePath = true
          break
        }
      }
    }
    if (samePath) {
      for (let a = 0; a < levelNumber - 1; a++) {
        relativePath += '../'
      }
      for (let a = levelNumber; a > 0; a--) {
        let targetPathData = targetPathArray[a]
        if (a == 1) relativePath += targetPathData
        else relativePath += targetPathData + '/'
      }
    } else {
      levelNumber = currentPathArray.length - 1
      for (let a = 0; a < levelNumber; a++) {
        relativePath += '../'
      }
      for (let a = 0; a < targetPathArray.length; a++) {
        let targetPathData = targetPathArray[a]
        if (a == targetPathArray.length - 1) relativePath += targetPathData
        else relativePath += targetPathData + '/'
      }
    }
    return relativePath
  }
  //获取时间戳
  getTimestamp() {
    return Date.parse(new Date())
  }
  /**
   * 参数：object key对应class，value对应true或false
   * 介绍：获取class的方法，使用方法同ng，比较方便多class生成
   */
  getClassName(params) {
    if (this.judgeNull(params)) return ''
    if (!this.judgeObject(params)) {
      console.error('expect object params')
      return ''
    }
    let className = ''
    for (var name in params) {
      if (params[name]) className += ' ' + name
    }
    return className.replace(/ /, '')
  }
}

export default common