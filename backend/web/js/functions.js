(function(global){
    /**
     * OG global
     * @type {Object}
     */
    var og = global.og = {};

    /**
     * Удаление элемента массива или объекта по ключу
     * @param objOrArray
     * @param key
     */
    og.del = function(objOrArray,key){
        if (angular.isArray(objOrArray)) {
            objOrArray.splice(+key,1);
        } else if (angular.isObject(objOrArray)){
            objOrArray[key] = undefined;
            delete(objOrArray[key]);
        }
    };

    og.findArrayIndex = function(array,key,match){
        var index = false;
        function findEquals(array,match) {
            $.each(array,function(i,itemValue){
                if (itemValue[key]==match) {
                    index = i;
                }
            });
            return index;
        }
        return findEquals(array,match);
    };

    og.extModel = function(list, source, byField) {

        var model = og.findInBy(list, byField, source[byField]);

        if(model) {
            // дополняем кастомными полями одинаковые модели ))))))))))))))))))))))
            $.extend(model, source);
            $.extend(source, model);
        }
        return {
            list: list,
            source: source
        }

    };

    /**
     * Поиск элемента(-ов) массива по значению ключа
     * @param array
     * @param key
     * @param match
     * @param {boolean} many поиск одного или всех совпадений
     * @returns {Array}
     */
    og.findInBy = function(array,key,match,many){
        var done,find = many ? [] : undefined;
        angular.forEach(array,function(itemValue){
            if (itemValue && !done && itemValue[key]==match) {
                if (!many) {
                    find = itemValue;
                    done = true;
                } else {
                    find.push(itemValue);
                }
            }
        });
        return find;
    };

    og.findInByMask = function(array,key,match,many){
        var done,find = many ? [] : undefined;
        angular.forEach(array,function(itemValue){
            if (!done && (itemValue[key].indexOf(match)+1)) {
                if (!many) {
                    find = itemValue;
                    done = true;
                } else {
                    find.push(itemValue);
                }
            }
        });
        return find;
    };

    og.findInArray = function(array,match){
        var find = false;
        function findEquals(array,match) {
            $.each(array,function(i,itemValue){
                if (itemValue==match) {
                    find = true;
                }
            });
            return find;
        }
        return findEquals(array,match);
    };

    /**
     * Удаление элемента(-ов) массива по значению ключа
     * @param array
     * @param key
     * @param match
     */
    og.delInBy = function(array,key,match){
        angular.forEach(array,function(itemValue,i){
            if (itemValue[key]==match) {
                og.del(array,i);
            }
        });
    };

    /**
     * Вспомогательные методы работы с коллекцией элементов
     * @param {Array} collection
     * @returns {{save: Function, delete: Function}}
     */
    og.collection = function(object,variable) {
        object[variable] = object[variable]||[];
        return {
            /**
             * Сохранение изменений (добавление нового) элемента коллекции по ключу
             * @param item
             * @param by
             */
            save: function(item,by){
                by = by||'uid';
                var exist = og.findInBy(object[variable],by,item[by]);
                if (exist) {
                    angular.extend(exist,item);
                } else {
                    object[variable].push(item);
                }
            },
            /**
             * Удаление элемента коллекции по ключу
             * @param item
             * @param by
             */
            delete: function(item,by){
                by = by||'uid';
                og.delInBy(object[variable],by,item[by]);
            }
        };
    };

    /**
     *
     */
    og.ucfirst = function(string){
        return string.charAt(0).toUpperCase() + string.slice(1);
    };

    og.byteTo = function(byte) {

        var fsize = 0;
        var fsizekb = byte / 1024;
        var fsizemb = fsizekb / 1024;
        var fsizegb = fsizemb / 1024;
        var fsizetb = fsizegb / 1024;

        if (fsizekb <= 1024) {
            fsize = fsizekb.toFixed(3) + ' кб';
        } else if (fsizekb >= 1024 && fsizemb <= 1024) {
            fsize = fsizemb.toFixed(3) + ' мб';
        } else if (fsizemb >= 1024 && fsizegb <= 1024) {
            fsize = fsizegb.toFixed(3) + ' гб';
        } else {
            fsize = fsizetb.toFixed(3) + ' тб';
        }
        return fsize;

    };

    /**
     *
     * @param input
     * @param procName
     * @param procValue
     * @returns {*}
     */
    og.map = function(input,procName,procValue){
        var result;
        if (angular.isArray(input)) {
            result = [];
            angular.forEach(input,function(val){
                result.push(procName(val));
            });
        } else if (angular.isObject(input)) {
            result = {};
            if (procValue==undefined) {
                procValue = procName;
                procName = function(name){ return name };
            }
            angular.forEach(input,function(val,name){
                result[procName(name,val)] = procValue(val);
            });
        }
        return result;
    };

    og.random = function(length){
        return Math.round(Math.random()*Math.pow(10,length||10));
    };

    og.groupByDate = function(timelineData,options) {
        options = options||{};

        options.step = options.step||'d';

        options.dateProp = options.dateProp||'start_dt';

        if (!options.groupFormat) {
            if (options.step=='d') {
                options.groupFormat = 'LL';
            } else if (options.step=='M') {
                options.groupFormat = 'MMMM YYYY';
            } else if (options.step=='y') {
                options.groupFormat = 'YYYY';
            }
        }

        var groupedData = {};

        var currentGroup = {};

        var setCurrentGroup = function(event){
            currentGroup.label = moment(event[options.dateProp]).format(options.groupFormat);
            currentGroup.startTime = moment(event[options.dateProp]);
            currentGroup.endTime = moment(currentGroup.startTime).endOf(options.step);
            groupedData[currentGroup.label] = groupedData[currentGroup.label]||[];
            groupedData[currentGroup.label].push(event);
        };

        angular.forEach(timelineData,function(event){
            if (!currentGroup.label) {
                setCurrentGroup(event);
            } else {
                if (currentGroup.endTime.isBefore(event[options.dateProp])) {
                    groupedData[currentGroup.label].push(event);
                } else {
                    setCurrentGroup(event);
                }
            }
        });

        return groupedData;
    };

    String.prototype.shuffle = function () {
        var a = this.split(""),
            n = a.length;

        for(var i = n - 1; i > 0; i--) {
            var j = Math.floor(Math.random() * (i + 1));
            var tmp = a[i];
            a[i] = a[j];
            a[j] = tmp;
        }
        return a.join("");
    };

    String.prototype.secondToTime = function () {
        var sec_num = parseInt(this, 10); // don't forget the second param
        var hours   = Math.floor(sec_num / 3600);
        var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
        var seconds = sec_num - (hours * 3600) - (minutes * 60);

        if (hours   < 10) {hours   = "0"+hours;}
        if (minutes < 10) {minutes = "0"+minutes;}
        if (seconds < 10) {seconds = "0"+seconds;}
        var time    = hours+':'+minutes+':'+seconds;
        return time;
    };

})(this);
