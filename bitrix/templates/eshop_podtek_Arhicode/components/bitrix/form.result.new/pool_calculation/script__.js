BX.namespace('BX.PoolCalculationPrise');
(function() {

    BX.PoolCalculationPrise = {
        init: function(parameters)
        {
            this.params = parameters;
            this.arProductPrice = parameters.arProductPrice;
            this.dpId = parameters.dpId;
            this.workPrice = 0;
            this.productPrice = 0;
            this.arPrice = [];
            this.workCatalogID = parameters.workCatalogID;

            this.pollSize = {
                width: 0,
                length: 0,
                deep: 0,
                validate: function(){
                    this.width = parseFloat(this.width);
                    if(typeof this.width != 'number') this.width = 0;

                    this.length = parseFloat(this.length);
                    if(typeof this.length != 'number') this.length = 0;

                    this.deep = parseFloat(this.deep);
                    if(typeof this.deep != 'number') this.deep = 0;
                },
                volume: function () {
                    this.validate();
                    return Math.ceil(this.width *this.length * this.deep);
                },
                area: function () {
                    this.validate();
                    return Math.ceil(this.width * this.length);
                },
                perimeter: function () {
                    this.validate();
                    return Math.ceil(2*(this.width + this.length));
                },
                poolArea: function () {
                    this.validate();
                    return Math.ceil(((this.width * this.length) + 2 * this.deep * (this.width  + this.length)) * 1.2 );
                },
            };

            this.obFormTtable = $('.form-table.data-table')[0];

            this.inputTopLevel = $('.question.block1-left input', this.obFormTtable);
            for (i = 0; i < this.inputTopLevel.length; i++)
                BX.bind(this.inputTopLevel[i], 'click', BX.proxy(this.clickInput, this));

            this.inputInnerLevel = $('.question.block1-right input',this.obFormTtable);
            for (i = 0; i < this.inputInnerLevel.length; i++)
                BX.bind(this.inputInnerLevel[i], 'click', BX.proxy(this.calculatePrice, this));

            this.obInputSize = $('.el-question.el-text input', this.obFormTtable);
            for (i = 0; i < this.obInputSize.length; i++)
                BX.bind(this.obInputSize[i], 'change', BX.proxy(this.calculatePrice, this));

            this.currentDependentBlock = false;
            this.dependentInput = [];

            this.initDependentBlock();
            this.calculatePrice();

            //console.log('BXPoolCalculationPrise::');
            //console.log(parameters);
            //console.log(this);
        },

        initDependentBlock: function()
        {
            var i, value, v;
            for (i = 0; i < this.inputTopLevel.length; i++)
            {
                value = $(this.inputTopLevel[i]).attr(this.dpId);
                if(value)
                {
                    v = value.split('_');
                    if(v.length > 1 && this.inputTopLevel[i].checked) {
                        this.currentDependentBlock = value;
                        $('#' + value).show();
                        this.dependentInput[value] = $('#' + value + ' input');
                        this.dependentInput[this.currentDependentBlock][0].checked = true;
                    } else if (v.length > 1) {
                        $('#' + value).hide();
                        this.dependentInput[value] = $('#' + value + ' input');
                    }
                }
            }
        },

        clickInput: function (e)
        {
            var i, value, v, input,
                e = e.target;

            //console.log(e);
            value = $(e).attr(this.dpId);
            if (value)
            {
                v = value.split('_');
                if(v.length > 1 && e.checked)
                {
                    // uncheck all hide input
                    for(i=0; i < this.dependentInput[this.currentDependentBlock].length; i++)
                        this.dependentInput[this.currentDependentBlock][i].checked = false;

                    $('#' + this.currentDependentBlock).hide();
                    this.currentDependentBlock = value;
                    $('#' + value).show();
                    this.dependentInput[this.currentDependentBlock][0].checked = true;
                }

                this.calculatePrice();
                //console.log(this);
            }

        },

        tryParseJSON: function (jsonString)
        {
            try {
                var o = JSON.parse(jsonString);
                if (o && typeof o === "object") {
                    return o;
                }
            }
            catch (e) { }
            return false;
        },

        calculatePrice: function ()
        {
            //console.log('1: calculatePrice:');
            this.arPrice = []; // головний масив з цінами для (товарів і робіт)
            var i, j,
                value = 0;
            var valueSize = 0;

            // формуємо масив цін для товарів внутрфшньої категорії
            for (i = 0; i < this.inputInnerLevel.length; i++)
            {
                if(this.inputInnerLevel[i].checked)
                {
                    value = $(this.inputInnerLevel[i]).attr(this.dpId);
                    this.arPrice.push(parseInt(value));
                    //console.log(this.inputInnerLevel[i]);
                }
            }

            value = 0;
            // формуємо масив цін для (товарів і робіт) базової категорії
            for (i = 0; i < this.inputTopLevel.length; i++)
            {
                value = $(this.inputTopLevel[i]).attr(this.dpId);

                if(this.inputTopLevel[i].checked)
                {
                    if (parseInt(value) > 1) // якщо input з ІД товара
                    {
                        value = parseInt(value);
                        if (value > 0 )
                            this.arPrice.push(parseInt(value));

                        //console.log(value);
                    }
                    else
                    {
                        value = value.replace(/'/g, '"');
                        if (this.tryParseJSON(value))
                        {
                            value = this.tryParseJSON(value);
                            var arrValue = 0;

                            //console.log('#');
                            if(value) {
                                for (j in value) {
                                    //console.log('#');
                                    //console.log(j);
                                    //console.log(value[j]);
                                    //console.log(this.pollSize.volume());

                                    if (this.pollSize.volume() <= parseInt(j)) {
                                        arrValue = value[j];
                                        break;
                                    }
                                }
                            }
                            this.arPrice.push(arrValue);
                            //console.log(this.arPrice);
                        }
                    }
                    //console.log(this.inputTopLevel[i]);
                }
                else if(value == 'width' || value == 'length' || value == 'deep') // якщо input з розмірами для басайна
                {
                    valueSize = parseFloat(this.inputTopLevel[i].value); // беремо введені значення
                    if(valueSize > 0)
                    {
                        this.pollSize[value] = valueSize;
                        this.inputTopLevel[i].style.borderBottomColor = 'black';
                    }
                    else
                    {
                        this.pollSize[value] = 0;
                        this.inputTopLevel[i].style.borderBottomColor = 'red';
                    }
                }

            }
            //console.log('--->>');
            //console.log(this.arPrice);
            //console.log(this.pollSize);
            //console.log('--->>');
            this.getPrice();
        },

        getPrice:function ()
        {
            this.workPrice = 0;
            this.productPrice = 0;

            var i, j,
                price,
                strPrice,
                quantity,
                action,
                perimeterNew;

            //console.log('2: getPrice:');
            //console.log('AP:'+this.pollSize.poolArea() +' -- ' + this.pollSize.perimeter());
            for(i = 0; i < this.arPrice.length; i++)
            {
                if(this.arProductPrice[this.arPrice[i]]) // шукаємо в списку продуктів наш продукт по ІД
                {
                    //console.log(this.arProductPrice[this.arPrice[i]]);
                    price = this.arProductPrice[this.arPrice[i]];
                    for (j in price)
                    {
                        strPrice = price[j].QUANTITY.toString(); // продукт що розраховується
                        if(strPrice.length > 5)
                        {
                            // товар для розрахунку
                            quantity = parseInt(strPrice.slice(0, strPrice.length-5));
                            action = strPrice.slice(strPrice.length-5, strPrice.length);

                            switch (action)
                            {
                                case '00000': // кількість товару множимо на ціну
                                    //console.log(j + ' #price: ' + parseFloat(price[j].BASE_PRICE) + ' * ' + quantity + ' = ' + (parseFloat(price[j].BASE_PRICE) * quantity));
                                    if(price[j].BLOCK_ID == this.workCatalogID) this.workPrice += (parseFloat(price[j].BASE_PRICE) * quantity);
                                        else this.productPrice += (parseFloat(price[j].BASE_PRICE) * quantity);
                                    break;
                                case '55555':
                                    break;
                                case '66666':
                                    break;
                                case '77777': // ціну (штук на 1м) множимо на периметер басейна
                                    //console.log(j + ' #price: ' + parseFloat(price[j].BASE_PRICE) + ' * ' + quantity +' * '+ this.pollSize.perimeter() +' = ' + (parseFloat(price[j].BASE_PRICE) * quantity * this.pollSize.perimeter()));
                                    if(price[j].BLOCK_ID == this.workCatalogID) this.workPrice += Math.ceil( parseFloat(price[j].BASE_PRICE) * quantity * this.pollSize.perimeter() );
                                        else this.productPrice += Math.ceil(parseFloat(price[j].BASE_PRICE) * quantity * this.pollSize.perimeter());
                                    break;
                                case '88888': // (1шт 2 поточних метра)
                                    if( (this.pollSize.perimeter() % quantity) > 0) perimeterNew =  (this.pollSize.perimeter() / quantity) + 1;
                                    else perimeterNew = this.pollSize.perimeter() / quantity;

                                    //console.log(j + ' #price: ' + parseFloat(price[j].BASE_PRICE) + ' * ' +  perimeterNew +' = ' + (Math.ceil( parseFloat(price[j].BASE_PRICE) *  perimeterNew )));
                                    if(price[j].BLOCK_ID == this.workCatalogID) this.workPrice += Math.ceil( parseFloat(price[j].BASE_PRICE) *  perimeterNew );
                                        else this.productPrice += Math.ceil(parseFloat(price[j].BASE_PRICE) * perimeterNew );
                                    break;
                                case '99999':
                                    //console.log(j + ' #price: ' + parseFloat(price[j].BASE_PRICE) + ' * ' + quantity + ' * '+ this.pollSize.poolArea() +' = ' + (Math.ceil( parseFloat(price[j].BASE_PRICE) * quantity * this.pollSize.poolArea() )));
                                    if(price[j].BLOCK_ID == this.workCatalogID) this.workPrice += Math.ceil( parseFloat(price[j].BASE_PRICE) * quantity * this.pollSize.poolArea() );
                                        else this.productPrice += Math.ceil(parseFloat(price[j].BASE_PRICE) * quantity * this.pollSize.poolArea() );
                                    break;
                            }

                            //console.log('1 >> '+ j +' | ' + price[j].BLOCK_ID+' .. ' + price[j].BASE_PRICE+' : '+quantity +' : '+action);
                        }
                        else // якщо звичайний товар
                        {
                            //console.log('2 >>' + j +' | ' + price[j].BLOCK_ID );
                            //console.log(price[j]);
                            if(price[j].BLOCK_ID == this.workCatalogID) // 11 - каталог для розрахунку вартості робіт
                            {
                                //console.log(j + ' price: ' + parseFloat(price[j].BASE_PRICE) + ' * ' +  parseInt(price[j].QUANTITY) + ' = ' + (parseFloat(price[j].BASE_PRICE) *  parseInt(price[j].QUANTITY)));
                                this.workPrice += (parseFloat(price[j].BASE_PRICE) * parseInt(price[j].QUANTITY));
                            }
                            else
                            {
                                //console.log(j + ' price: ' + parseFloat(price[j].BASE_PRICE) + ' * ' +  parseInt(price[j].QUANTITY) + ' = ' + (parseFloat(price[j].BASE_PRICE) *  parseInt(price[j].QUANTITY)));
                                this.productPrice += (parseFloat(price[j].BASE_PRICE) * parseInt(price[j].QUANTITY));
                            }
                        }
                    }
                }
            }

            //console.log('this.productPrice=');
            //console.log(this.productPrice);
            // ЦІНА
            $('#pool-price-material').html(this.bxFormatPrice(parseFloat(this.productPrice).toFixed(2)) + ' руб.');
            $('#pool-price-work').html(this.bxFormatPrice(parseFloat(this.workPrice).toFixed(2)) + ' руб.');
            $('#pool-price-all').html(this.bxFormatPrice(parseFloat(this.workPrice + this.productPrice).toFixed(2)) + ' руб.');
        },

        bxFormatPrice: function(price)
        {
            var result = '';
            if (typeof(price) != 'undefined')
            {
                if (typeof(price) == 'number')
                    price = price.toString();
                if (price.length > 0)
                {
                    var testPrice = /^([\d]+)|([\d]+\.|,[\d]+)$/;
                    if (testPrice.test(price))
                    {
                        var str, integral, decimal, delim, regex;

                        regex = /\.|,[\d]+$/ig;
                        delimPos = price.search(regex);
                        if (delimPos >= 0)
                        {
                            integral = price.substr(0, delimPos);
                            decimal = price.substr(delimPos+1);
                        }
                        else
                        {
                            integral = price;
                            decimal = '';
                        }

                        str = integral;
                        var blockSize = 3;
                        if (str.length > blockSize)
                        {
                            while (str.length > 0)
                            {
                                if (str.length > blockSize)
                                {
                                    result = ' ' + str.substr((blockSize*(-1)), blockSize) + result;
                                    str = str.substr(0, (str.length - blockSize));
                                }
                                else
                                {
                                    result = str + result;
                                    str = '';
                                }
                            }
                            result = decimal.length > 0 ? result+'.'+decimal : result;
                        }
                        else
                        {
                            result = str + (decimal.length > 0 ? '.'+decimal : '');
                        }
                    }
                    else
                    {
                        result = price;
                    }
                }
            }
            return result;
        }
    };

})();