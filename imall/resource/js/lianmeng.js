!
function (a) {
    var b = Math.round(Math.random()),
        c = function (a, b) {
            this.tl = null,
            this.$target = a,
            this.comp = b,
            this.initialise()
        };
    c.prototype = {
            initialise: function () {
                var b = this,
                    d = !1;
                a(window).resize(function () {
                        d !== !1 && clearTimeout(d),
                        d = setTimeout(function () {
                            b.onresize()
                        }, 100)
                    }),
                b.$target.css("display", "block"),
                this.changeViewHeight = function () {
                        return c.prototype.changeViewHeight.apply(b, arguments)
                    },
                this.end = function () {
                        return c.prototype.end.apply(b, arguments)
                    },
                b.tl = new TimelineMax({
                        delay: .8,
                        paused: !0
                    }),
                b.tl.appendMultiple([TweenMax.from(b.$target.find(".person"), .3, {
                        autoAlpha: 0
                    }), TweenMax.from(b.$target.find(".person"), .8, {
                        y: 150,
                        ease: Cubic.easeOut
                    }), TweenMax.from(b.$target.find(".person .m2"), 0, {
                        delay: 1,
                        autoAlpha: 0,
                        ease: Linear.easeNone
                    }), TweenMax.from(b.$target.find(".fukidashi .wrap"), .4, {
                        delay: 1.2,
                        autoAlpha: 0,
                        ease: Linear.easeNone
                    }), TweenMax.from(b.$target.find(".fukidashi .wrap"), .8, {
                        delay: 1.2,
                        scaleX: 0,
                        scaleY: 0,
                        ease: Expo.easeOut
                    }), TweenMax.from(b.$target.find(".fukidashi .wrap"), .8, {
                        delay: 1.2,
                        y: 50,
                        ease: Expo.easeOut,
                        onComplete: b.end
                    })]),
                b.changeViewHeight()
            },
            end: function () {
                var b = this;
                TweenMax.to(b.$target, .3, {
                    delay: 2.4,
                    autoAlpha: 0,
                    ease: Linear.easeNone,
                    onStart: function () {
                        a(".pagewrap").addClass("active")
                    },
                    onComplete: function () {
                        a("body").addClass("active"),
                        b.comp()
                    }
                })
            },
            onresize: function () {
                var a = this;
                a.changeViewHeight(!0)
            },
            changeViewHeight: function () {
                var b = Math.max(a(window).height(), 600),
                    c = b / 750;
                TweenMax.to(a("#fi > .wrap"), .4, {
                        scaleX: c,
                        scaleY: c,
                        transformOrign: "50% 50%",
                        ease: Cubic.easeOut
                    })
            },
            setLoad: function () {
                var b = this,
                    c = Math.floor(8 * Math.random()) + 1;
                b.$target.find(".person .m1").attr("src", "images/fi/p-" + c + "_0.jpg"),
                b.$target.find(".person .m2").attr("src", "images/fi/p-" + c + "_1.jpg"),
                b.$target.find(".fukidashi img").attr("src", "images/fi/p-" + c + "_fukidashi.png"),
                b.loadmanager = new LoadManager,
                b.$target.find("img").each(function () {
                        b.loadmanager.add(a(this).attr("src"))
                    }),
                b.loadmanager.onComplete = function () {
                        b.moviePlay()
                    },
                b.loadmanager.start()
            },
            moviePlay: function () {
                var a = this;
                a.tl.play()
            }
        };
    var d = function (a, b, c) {
            this.$target = a,
            this.fType = b,
            this.origin = c,
            this.isVisible = !0,
            this.tl = null,
            this.initialise()
        };
    d.prototype = {
            initialise: function () {
                var b = this;
                TweenMax.set(b.$target.find(".fukidashi"), {
                    transformOrigin: b.origin
                }),
                b.tl = new TimelineMax({
                    paused: !0,
                    delay: .2
                }),
                b.tl.appendMultiple(a("html").hasClass("oldie") ? [TweenMax.from(b.$target.find(".m2"), .1, {
                    autoAlpha: 0,
                    ease: Linear.easeNone
                }), TweenMax.from(b.$target.find(".fukidashi"), .2, {
                    delay: .1,
                    autoAlpha: 0,
                    ease: Linear.easeNone
                }), TweenMax.from(b.$target.find(".fukidashi"), .3, {
                    delay: .1,
                    y: 20,
                    ease: Expo.easeOut
                })] : [TweenMax.from(b.$target.find(".m2"), .1, {
                    autoAlpha: 0,
                    ease: Linear.easeNone
                }), TweenMax.from(b.$target.find(".fukidashi"), .2, {
                    delay: .1,
                    autoAlpha: 0,
                    ease: Linear.easeNone
                }), TweenMax.from(b.$target.find(".fukidashi"), .3, {
                    delay: .1,
                    scale: 0,
                    ease: Expo.easeOut
                }), TweenMax.from(b.$target.find(".fukidashi"), .3, {
                    delay: .1,
                    y: 50,
                    ease: Expo.easeOut
                })]),
                this.action = function () {
                    return d.prototype.action.apply(b, arguments)
                },
                this.hide = function () {
                    return d.prototype.hide.apply(b, arguments)
                },
                this.show = function () {
                    return d.prototype.show.apply(b, arguments)
                },
                b.$target.find(".cover").hover(function () {
                    b.$target.trigger("onPersonHover"),
                    b.action()
                }, function () {
                    b.$target.trigger("onPersonOut"),
                    b.offAction()
                })
            },
            action: function () {
                var a = this;
                a.isVisible && (a.tl.play(), a.$target.find(".fukidashi").css("z-index", 10), a.$target.trigger("onAction"))
            },
            offAction: function () {
                var a = this;
                a.tl.reverse(),
                a.$target.find(".fukidashi").css("z-index", 5),
                a.$target.trigger("onOffAction")
            },
            hide: function () {
                var a = this;
                a.isVisible && (a.isVisible = !1, TweenMax.to(a.$target, .3, {
                    autoAlpha: 0,
                    ease: Linear.easeNone
                }))
            },
            show: function () {
                var a = this;
                a.isVisible || (a.isVisible = !0, TweenMax.to(a.$target, .3, {
                    autoAlpha: 1,
                    ease: Linear.easeNone
                }))
            }
        };
    var e = function (a) {
            function c(a) {
                for (var b, c, d = a.length; d;) c = Math.floor(Math.random() * d--),
                b = a[d],
                a[d] = a[c],
                a[c] = b;
                return a
            }
            this.$target = a,
            this.$persons = a.find(".person"),
            this.ptimer = !1,
            this.personW = 1250,
            this.showIndex = 0,
            this.showAry = c([0, 1, 2, 3, 4, 5, 6, 7]);
            var e = [new d(a.find(".pp1"), b, "left bottom"), new d(a.find(".pp2"), b, "left bottom"), new d(a.find(".pp3"), b, "right bottom"), new d(a.find(".pp4"), b, "right bottom"), new d(a.find(".pp5"), b, "left bottom"), new d(a.find(".pp6"), b, "left bottom"), new d(a.find(".pp7"), b, "right bottom"), new d(a.find(".pp8"), b, "right bottom")];
            this.childs = e,
            this.startTL = null,
            this.isInit = !1,
            this.initialise()
        };
    e.prototype = {
            initialise: function () {
                var b = this,
                    c = !1;
                a(window).resize(function () {
                        c !== !1 && clearTimeout(c),
                        c = setTimeout(function () {
                            b.onresize()
                        }, 100)
                    });
                for (var d = 0; d < b.childs.length; d++) b.childs[d].$target.on("onPersonHover", function () {
                        b.actionClear()
                    }).on("onPersonOut", function () {
                        b.setNextHover()
                    });
                b.onresize(),
                this.changeViewMargin = function () {
                        return e.prototype.changeViewMargin.apply(b, arguments)
                    },
                this.setNext = function () {
                        return e.prototype.setNext.apply(b, arguments)
                    },
                this.next = function () {
                        return e.prototype.next.apply(b, arguments)
                    },
                this.setStart = function () {
                        return e.prototype.setStart.apply(b, arguments)
                    },
                this.setFitstTimerStart = function () {
                        return e.prototype.setFitstTimerStart.apply(b, arguments)
                    },
                b.startTl = new TimelineMax({
                        delay: 0,
                        paused: !0,
                        onComplete: b.setFitstTimerStart
                    }),
                a("html").hasClass("oldie") || b.startTl.appendMultiple([TweenMax.from(a(".circlearea > .circle"), .6, {
                        delay: .5,
                        scale: 0,
                        ease: Back.easeOut
                    }), TweenMax.from(a(".circlearea > .wrap"), .5, {
                        delay: 1,
                        scale: 1.3,
                        ease: Expo.easeOut
                    }), TweenMax.from(a(".circlearea > .wrap"), .2, {
                        delay: 1,
                        autoAlpha: 0,
                        ease: Linear.easeNone
                    })]);
                for (var f = [], g = a(window).width(), d = 0; d < b.childs.length; d++) g < b.personW && 0 == d || g < b.personW && d == b.childs.length - 1 ? TweenMax.set(b.childs[d].$target, {
                        autoAlpha: 0
                    }) : f.push(TweenMax.from(b.childs[d].$target, .5, {
                        delay: .4 * Math.random() + .1,
                        autoAlpha: 0,
                        ease: Linear.easeNone
                    }));
                b.startTl.appendMultiple(f)
            },
            setStart: function () {
                var a = this;
                a.startTl.play(),
                a.isInit = !0
            },
            setFitstTimerStart: function () {
                var a = this;
                a.isInit = !0,
                this.ptimer = setTimeout(function () {
                    a.onresize(),
                    a.next()
                }, 2e3)
            },
            setNext: function () {
                var a = this;
                a.ptimer !== !1 && clearTimeout(a.ptimer),
                a.ptimer = setTimeout(function () {
                    a.next()
                }, 3e3)
            },
            setNextHover: function () {
                var a = this;
                a.ptimer !== !1 && clearTimeout(a.ptimer),
                a.ptimer = setTimeout(function () {
                    a.next()
                }, 1e3)
            },
            next: function () {
                for (var b = this, c = 0; c < b.childs.length; c++) b.childs[c].offAction();
                var d = b.showAry[b.showIndex];
                a(window).width() < b.personW && 0 == d || a(window).width() < b.personW && d == b.showAry.length - 1 ? (b.showIndex++, b.next()) : (b.childs[b.showAry[b.showIndex]].action(), b.showIndex++, b.setNext()),
                b.showIndex > 7 && (b.showIndex = 0)
            },
            actionClear: function () {
                for (var a = this, b = 0; b < a.childs.length; b++) a.childs[b].offAction();
                clearTimeout(a.ptimer)
            },
            onresize: function () {
                var a = this;
                a.changeViewMargin(!0)
            },
            changeViewMargin: function () {
                var b = this,
                    c = Math.min(a(window).width(), 2e3) - 1450;
                if (0 > c) b.$persons.css("margin", "0 0");
                else {
                        var d = c / 16;
                        b.$persons.css("margin", "0 " + d + "px")
                    }
                b.isInit && (a(window).width() < b.personW ? (b.childs[0].hide(), b.childs[b.childs.length - 1].hide()) : (b.childs[0].show(), b.childs[b.childs.length - 1].show()))
            }
        },
    a(function () {
            var b = new e(a("#intro .girls-wrap"));
            //if (a.cookie("cc_topAccess")) 
			a("#fi").remove(),
            a(".pagewrap").addClass("active"),
            a("body").addClass("active"),
            b.setStart();
            //else {
            //    var d = new c(a("#fi"), function () {
            //        b.setStart()
            //    });
            //    d.setLoad(),
            //    a.cookie("cc_topAccess", 1, {
            //        path: "/"
            //    })
            //}
        })
}(jQuery);