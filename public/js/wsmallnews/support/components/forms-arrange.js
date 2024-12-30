// resources/js/forms/arrange.js
function arrangeFormField({ state, arrangeToRecursionKey, tableFields }) {
  return {
    state,
    arrangeToRecursionKey,
    tableFields,
    arrangeRealIdToTempId: [],
    arranges: [],
    recursions: [],
    existRandoms: [],
    init: function() {
      this.initArranges();
      this.initRecursions();
      if (!this.arranges) {
        this.autoFirst();
      }
      this.buildRecursionsTable();
      this.updateState();
      this.$watch("arranges", (arranges, oldArranges) => {
        this.buildRecursionsTable();
        this.updateState();
      });
      this.$watch("recursions", (recursions, oldRecursions) => {
        this.updateState();
      });
    },
    initArranges: function() {
      let stateArranges = this.state?.arranges ?? [];
      stateArranges.forEach((stateArrange, index) => {
        let currentArrange = stateArrange;
        if (currentArrange["temp_id"] == void 0 || !currentArrange["temp_id"]) {
          currentArrange["temp_id"] = this.tempRandom();
        } else {
          this.existRandoms.push(currentArrange["temp_id"]);
        }
        let currentChildren = [];
        stateArrange.children.forEach((children, idx) => {
          if (children["temp_id"] == void 0 || !children["temp_id"]) {
            children["temp_id"] = this.tempRandom();
          } else {
            this.existRandoms.push(children["temp_id"]);
          }
          if (children.id != void 0 && children.id) {
            this.arrangeRealIdToTempId[children.id] = children["temp_id"];
          }
          currentChildren.push(children);
        });
        this.arranges.push(currentArrange);
      });
    },
    initRecursions: function() {
      let stateRecursions = this.state?.recursions ?? [];
      stateRecursions.forEach((stateRecursion, index) => {
        let currentRecursion = stateRecursion;
        currentRecursion["temp_id"] = this.tempRandom(1e7, 99999999);
        if (Array.isArray(currentRecursion["arrange_temp_ids"]) && currentRecursion["arrange_temp_ids"].length > 0) {
          if (this.arranges.length == currentRecursion["arrange_temp_ids"].length) {
            this.recursions.push(currentRecursion);
          }
        } else {
          currentRecursion["arrange_temp_ids"] = [];
          let arrangeToRecursionIds = Array.isArray(currentRecursion[this.arrangeToRecursionKey]) ? currentRecursion[this.arrangeToRecursionKey] : currentRecursion[this.arrangeToRecursionKey].split(",");
          arrangeToRecursionIds.forEach((ids) => {
            if (this.arrangeRealIdToTempId[ids]) {
              currentRecursion["arrange_temp_ids"].push(this.arrangeRealIdToTempId[ids]);
            }
          });
          if (arrangeToRecursionIds.length == currentRecursion["arrange_temp_ids"].length) {
            this.recursions.push(currentRecursion);
          }
        }
      });
    },
    updateState: function() {
      let state2 = {};
      state2["arranges"] = this.arranges;
      state2["recursions"] = this.recursions;
      this.state = state2;
    },
    arrangeTemplate: function() {
      return {
        temp_id: this.tempRandom(),
        name: "",
        order_column: this.arranges.length,
        children: []
      };
    },
    childrenArrangeTemplate: function(index) {
      return {
        temp_id: this.tempRandom(),
        name: "",
        image: "",
        order_column: this.arranges[index].children.length
      };
    },
    // 自动初始化 arranges
    autoFirst: function() {
      this.arranges = [this.arrangeTemplate()];
      this.arranges[0].children.push(this.childrenArrangeTemplate(0));
    },
    // 添加主规格
    addArrange: function() {
      this.arranges.push(this.arrangeTemplate());
    },
    // 添加子规格
    addChildrenArrange: function(index) {
      this.arranges[index].children.push(this.childrenArrangeTemplate(index));
      if (this.arranges[index].children.length == 1) {
        this.recursions = [];
      }
    },
    deleteArrange: function(index) {
      if (this.arranges[index].children.length) {
        this.recursions = [];
      }
      this.arranges.splice(index, 1);
    },
    // 删除子规格
    deleteChildrenArrange: function(parentIndex, index) {
      let data = this.arranges[parentIndex].children[index];
      this.arranges[parentIndex].children.splice(index, 1);
      if (this.arranges[parentIndex].children.length <= 0) {
        this.recursions = [];
      } else {
        let deleteRecursionIndexArr = [];
        this.recursions.forEach((recursion, index2) => {
          recursion.arrange_texts.forEach((arrange_text, ix) => {
            if (arrange_text == data.name) {
              deleteRecursionIndexArr.push(index2);
            }
          });
        });
        deleteRecursionIndexArr.sort(function(a, b) {
          return b - a;
        });
        deleteRecursionIndexArr.forEach((recursionIndex, index2) => {
          this.recursions.splice(recursionIndex, 1);
        });
      }
    },
    // 重新构建 recursions 表格
    buildRecursionsTable: function() {
      let arrangeChildrenIdArr = [];
      this.arranges.forEach((arrange, key) => {
        let children = arrange.children;
        let childrenIdArr = [];
        if (children.length > 0) {
          children.forEach((child, k) => {
            childrenIdArr.push(child.temp_id);
          });
          arrangeChildrenIdArr.push(childrenIdArr);
        }
      });
      this.recursionFunc(arrangeChildrenIdArr);
    },
    // 递归
    recursionFunc: function(arrangeChildrenIdArr, arrangeK = 0, temp = []) {
      if (arrangeK == arrangeChildrenIdArr.length && arrangeK != 0) {
        let tempDetail = [];
        let tempDetailIds = [];
        temp.forEach((item, index) => {
          this.arranges.forEach((arrange, inx) => {
            arrange.children.forEach((child, ix) => {
              if (item == child.temp_id) {
                tempDetail.push(child.name);
                tempDetailIds.push(child.temp_id);
              }
            });
          });
        });
        let flag = false;
        for (let i in this.recursions) {
          this.recursions[i].arrange_temp_ids.sort();
          tempDetailIds.sort();
          if (this.recursions[i].arrange_temp_ids.join(",") == tempDetailIds.join(",")) {
            flag = i;
            break;
          }
        }
        if (!flag) {
          let pushRecursion = {
            temp_id: this.tempRandom(1e7, 99999999),
            arrange_texts: tempDetail,
            arrange_temp_ids: tempDetailIds
          };
          this.tableFields.forEach((field) => {
            pushRecursion[field.field] = field.default;
          });
          pushRecursion[this.arrangeToRecursionKey] = [];
          this.recursions.push(pushRecursion);
        } else {
          this.recursions[flag].arrange_texts = tempDetail;
          this.recursions[flag].arrange_temp_ids = tempDetailIds;
        }
      }
      if (arrangeChildrenIdArr.length) {
        arrangeChildrenIdArr[arrangeK] && arrangeChildrenIdArr[arrangeK].forEach((cv, ck) => {
          temp[arrangeK] = arrangeChildrenIdArr[arrangeK][ck];
          this.recursionFunc(arrangeChildrenIdArr, arrangeK + 1, temp);
        });
      }
    },
    tempRandom: function(min = 1e6, max = 9999999) {
      let random;
      do {
        random = Math.floor(Math.random() * (max - min + 1)) + min;
      } while (this.existRandoms.includes(random));
      this.existRandoms.push(random);
      return random;
    }
  };
}
export {
  arrangeFormField as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsiLi4vLi4vanMvZm9ybXMvYXJyYW5nZS5qcyJdLAogICJzb3VyY2VzQ29udGVudCI6IFsiZXhwb3J0IGRlZmF1bHQgZnVuY3Rpb24gYXJyYW5nZUZvcm1GaWVsZCh7IHN0YXRlLCBhcnJhbmdlVG9SZWN1cnNpb25LZXksIHRhYmxlRmllbGRzIH0pIHtcbiAgICByZXR1cm4ge1xuICAgICAgICBzdGF0ZSxcbiAgICAgICAgYXJyYW5nZVRvUmVjdXJzaW9uS2V5LFxuICAgICAgICB0YWJsZUZpZWxkcyxcbiAgICAgICAgYXJyYW5nZVJlYWxJZFRvVGVtcElkOiBbXSxcbiAgICAgICAgYXJyYW5nZXM6IFtdLFxuICAgICAgICByZWN1cnNpb25zOiBbXSxcbiAgICAgICAgZXhpc3RSYW5kb21zOiBbXSxcbiAgICAgICAgaW5pdDogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICAvLyBcdTUyMURcdTU5Q0JcdTUzMTYgYXJyYW5nZXMsIFx1N0VDNFx1NEVGNlx1NzY4NFx1NjYzRVx1NzkzQVx1OTY5MFx1ODVDRlx1NEYxQVx1OTFDRFx1NjVCMFx1OEQ3MFx1OEJFNVx1NjVCOVx1NkNENSAoXHU2QkQ0XHU1OTgyXHU1MjA3XHU2MzYyXHU1MzU1XHU4OUM0XHU2ODNDXHU1OTFBXHU4OUM0XHU2ODNDKVxuICAgICAgICAgICAgdGhpcy5pbml0QXJyYW5nZXMoKTtcblxuICAgICAgICAgICAgLy8gXHU1MjFEXHU1OUNCXHU1MzE2IHJlY3Vyc2lvbnNcbiAgICAgICAgICAgIHRoaXMuaW5pdFJlY3Vyc2lvbnMoKTtcblxuICAgICAgICAgICAgLy8gXHU1OTgyXHU2NzlDXHU2Q0ExXHU2NzA5IGFycmFuZ2VzIFx1RkYwQ1x1NTIxOVx1OUVEOFx1OEJBNFx1NTIxRFx1NTlDQlx1NTMxNlx1NEUwMFx1NEUyQVx1NEUzQlx1ODlDNFx1NjgzQ1x1RkYwQ1x1NUU3Nlx1OTY0NFx1NUUyNlx1NEUwMFx1NEUyQVx1NUI1MFx1ODlDNFx1NjgzQ1xuICAgICAgICAgICAgaWYgKCF0aGlzLmFycmFuZ2VzKSB7XG4gICAgICAgICAgICAgICAgdGhpcy5hdXRvRmlyc3QoKTtcbiAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgLy8gXHU2Nzg0XHU1RUZBIHJlY3Vyc2lvbiB0YWJsZVxuICAgICAgICAgICAgdGhpcy5idWlsZFJlY3Vyc2lvbnNUYWJsZSgpO1xuICAgICAgICAgICAgdGhpcy51cGRhdGVTdGF0ZSgpO1xuXG4gICAgICAgICAgICB0aGlzLiR3YXRjaCgnYXJyYW5nZXMnLCAoYXJyYW5nZXMsIG9sZEFycmFuZ2VzKSA9PiB7XG4gICAgICAgICAgICAgICAgdGhpcy5idWlsZFJlY3Vyc2lvbnNUYWJsZSgpO1xuICAgICAgICAgICAgICAgIHRoaXMudXBkYXRlU3RhdGUoKTtcbiAgICAgICAgICAgIH0pXG5cbiAgICAgICAgICAgIHRoaXMuJHdhdGNoKCdyZWN1cnNpb25zJywgKHJlY3Vyc2lvbnMsIG9sZFJlY3Vyc2lvbnMpID0+IHtcbiAgICAgICAgICAgICAgICB0aGlzLnVwZGF0ZVN0YXRlKCk7ICAgICAgICAgIC8vIHgtbW9kZWwgXHU3Njg0XHU2RDQ1XHU3RUQxXHU1QjlBXHU0RjFBXHU4MUVBXHU1MkE4XHU2NkY0XHU2NUIwIHN0YXRlLCBcdTRGNDZcdTY2MkZcdThGRDhcdTY2MkZcdTY2RjRcdTY1QjBcdTRFMDBcdTRFMEJcdTU5N0RcbiAgICAgICAgICAgIH0pXG4gICAgICAgIH0sXG4gICAgICAgIGluaXRBcnJhbmdlczogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBsZXQgc3RhdGVBcnJhbmdlcyA9IHRoaXMuc3RhdGU/LmFycmFuZ2VzID8/IFtdO1xuXG4gICAgICAgICAgICBzdGF0ZUFycmFuZ2VzLmZvckVhY2goKHN0YXRlQXJyYW5nZSwgaW5kZXgpID0+IHtcbiAgICAgICAgICAgICAgICBsZXQgY3VycmVudEFycmFuZ2UgPSBzdGF0ZUFycmFuZ2U7XG4gICAgICAgICAgICAgICAgaWYgKGN1cnJlbnRBcnJhbmdlWyd0ZW1wX2lkJ10gPT0gdW5kZWZpbmVkIHx8ICFjdXJyZW50QXJyYW5nZVsndGVtcF9pZCddKSB7XG4gICAgICAgICAgICAgICAgICAgIGN1cnJlbnRBcnJhbmdlWyd0ZW1wX2lkJ10gPSB0aGlzLnRlbXBSYW5kb20oKTtcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLmV4aXN0UmFuZG9tcy5wdXNoKGN1cnJlbnRBcnJhbmdlWyd0ZW1wX2lkJ10pOyAgICAgICAvLyBcdTRGRERcdTVCNThcdUZGMENcdTkwN0ZcdTUxNERcdTkxQ0RcdTU5MERcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgXG4gICAgICAgICAgICAgICAgbGV0IGN1cnJlbnRDaGlsZHJlbiA9IFtdO1xuICAgICAgICAgICAgICAgIHN0YXRlQXJyYW5nZS5jaGlsZHJlbi5mb3JFYWNoKChjaGlsZHJlbiwgaWR4KSA9PiB7XG4gICAgICAgICAgICAgICAgICAgIGlmIChjaGlsZHJlblsndGVtcF9pZCddID09IHVuZGVmaW5lZCB8fCAhY2hpbGRyZW5bJ3RlbXBfaWQnXSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgLy8gXHU0RTNBXHU2QkNGXHU0RTJBIFx1ODlDNFx1NjgzQ1x1OTg3OVx1NTg5RVx1NTJBMFx1NUY1M1x1NTI0RFx1OTg3NVx1OTc2Mlx1ODFFQVx1NTg5RVx1OEJBMVx1NjU3MFx1NTY2OFx1RkYwQ1x1NkJENFx1OEY4M1x1NTUyRlx1NEUwMFx1NzUyOFxuICAgICAgICAgICAgICAgICAgICAgICAgY2hpbGRyZW5bJ3RlbXBfaWQnXSA9IHRoaXMudGVtcFJhbmRvbSgpO1xuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5leGlzdFJhbmRvbXMucHVzaChjaGlsZHJlblsndGVtcF9pZCddKTsgICAgICAgLy8gXHU0RkREXHU1QjU4XHVGRjBDXHU5MDdGXHU1MTREXHU5MUNEXHU1OTBEXG4gICAgICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgICAgICBpZiAoY2hpbGRyZW4uaWQgIT0gdW5kZWZpbmVkICYmIGNoaWxkcmVuLmlkKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAvLyBcdThCQjBcdTVGNTVcdTg5QzRcdTY4M0NcdTk4NzlcdTc3MUZcdTVCOUUgaWQgXHU1QkY5XHU1RTk0XHU3Njg0IFx1NEUzNFx1NjVGNiBpZFxuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5hcnJhbmdlUmVhbElkVG9UZW1wSWRbY2hpbGRyZW4uaWRdID0gY2hpbGRyZW5bJ3RlbXBfaWQnXTtcbiAgICAgICAgICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICAgICAgICAgIGN1cnJlbnRDaGlsZHJlbi5wdXNoKGNoaWxkcmVuKVxuICAgICAgICAgICAgICAgIH0pXG5cbiAgICAgICAgICAgICAgICB0aGlzLmFycmFuZ2VzLnB1c2goY3VycmVudEFycmFuZ2UpXG4gICAgICAgICAgICB9KVxuICAgICAgICB9LFxuICAgICAgICBpbml0UmVjdXJzaW9uczogZnVuY3Rpb24oKSB7XG4gICAgICAgICAgICBsZXQgc3RhdGVSZWN1cnNpb25zID0gdGhpcy5zdGF0ZT8ucmVjdXJzaW9ucyA/PyBbXTtcblxuICAgICAgICAgICAgc3RhdGVSZWN1cnNpb25zLmZvckVhY2goKHN0YXRlUmVjdXJzaW9uLCBpbmRleCkgPT4ge1xuICAgICAgICAgICAgICAgIGxldCBjdXJyZW50UmVjdXJzaW9uID0gc3RhdGVSZWN1cnNpb247XG5cbiAgICAgICAgICAgICAgICAvLyBcdTU4OUVcdTUyQTBcdTRFMzRcdTY1RjYgaWRcbiAgICAgICAgICAgICAgICBjdXJyZW50UmVjdXJzaW9uWyd0ZW1wX2lkJ10gPSB0aGlzLnRlbXBSYW5kb20oMTAwMDAwMDAsIDk5OTk5OTk5KTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICAvLyBcdTdFQzRcdTU0MDggYXJyYW5nZV90ZW1wX2lkc1xuICAgICAgICAgICAgICAgIGlmIChBcnJheS5pc0FycmF5KGN1cnJlbnRSZWN1cnNpb25bJ2FycmFuZ2VfdGVtcF9pZHMnXSkgJiYgY3VycmVudFJlY3Vyc2lvblsnYXJyYW5nZV90ZW1wX2lkcyddLmxlbmd0aCA+IDApIHtcbiAgICAgICAgICAgICAgICAgICAgLy8gXHU1MjA3XHU2MzYyIGFycmFuZ2UgXHU3MkI2XHU2MDAxXHU1QkZDXHU4MUY0XHU3Njg0XHU5MUNEXHU2NUIwXHU1MjFEXHU1OUNCXHU1MzE2XG4gICAgICAgICAgICAgICAgICAgIGlmICh0aGlzLmFycmFuZ2VzLmxlbmd0aCA9PSBjdXJyZW50UmVjdXJzaW9uWydhcnJhbmdlX3RlbXBfaWRzJ10ubGVuZ3RoKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAvLyBcdTUzRUFcdTg5ODFcdTU0OEMgYXJyYW5nZXMgXHU5NTdGXHU1RUE2XHU1MzM5XHU5MTREXG4gICAgICAgICAgICAgICAgICAgICAgICB0aGlzLnJlY3Vyc2lvbnMucHVzaChjdXJyZW50UmVjdXJzaW9uKVxuICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAgICAgLy8gXHU3RjE2XHU4RjkxXHU2NUY2XHU1MDE5XHVGRjBDXHU1QzA2XHU3NzFGXHU1QjlFIGlkIFx1NjU3MFx1N0VDNFx1RkYwQ1x1NUZBQVx1NzNBRlx1RkYwQ1x1NjI3RVx1NTIzMFx1NUJGOVx1NUU5NFx1NzY4NFx1NEUzNFx1NjVGNiBpZCBcdTdFQzRcdTU0MDhcdTYyMTBcdTY1NzBcdTdFQzRcbiAgICAgICAgICAgICAgICAgICAgY3VycmVudFJlY3Vyc2lvblsnYXJyYW5nZV90ZW1wX2lkcyddID0gW107XG4gICAgXG4gICAgICAgICAgICAgICAgICAgIGxldCBhcnJhbmdlVG9SZWN1cnNpb25JZHMgPSBBcnJheS5pc0FycmF5KGN1cnJlbnRSZWN1cnNpb25bdGhpcy5hcnJhbmdlVG9SZWN1cnNpb25LZXldKSA/IFxuICAgICAgICAgICAgICAgICAgICAgICAgY3VycmVudFJlY3Vyc2lvblt0aGlzLmFycmFuZ2VUb1JlY3Vyc2lvbktleV0gOiBjdXJyZW50UmVjdXJzaW9uW3RoaXMuYXJyYW5nZVRvUmVjdXJzaW9uS2V5XS5zcGxpdCgnLCcpO1xuXG4gICAgICAgICAgICAgICAgICAgIGFycmFuZ2VUb1JlY3Vyc2lvbklkcy5mb3JFYWNoKChpZHMpID0+IHtcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmICh0aGlzLmFycmFuZ2VSZWFsSWRUb1RlbXBJZFtpZHNdKSB7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgY3VycmVudFJlY3Vyc2lvblsnYXJyYW5nZV90ZW1wX2lkcyddLnB1c2godGhpcy5hcnJhbmdlUmVhbElkVG9UZW1wSWRbaWRzXSk7XG4gICAgICAgICAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICAgICAgICAgIH0pXG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKGFycmFuZ2VUb1JlY3Vyc2lvbklkcy5sZW5ndGggPT0gY3VycmVudFJlY3Vyc2lvblsnYXJyYW5nZV90ZW1wX2lkcyddLmxlbmd0aCkge1xuICAgICAgICAgICAgICAgICAgICAgICAgLy8gXHU4MEZEXHU2MjdFXHU1MjMwXHU1MzM5XHU5MTREXHU3Njg0IGFycmFuZ2VzIFx1RkYwQ1x1NjI3RVx1NEUwRFx1NTIzMFx1NzY4NFx1NEUyMlx1NUYwM1xuICAgICAgICAgICAgICAgICAgICAgICAgdGhpcy5yZWN1cnNpb25zLnB1c2goY3VycmVudFJlY3Vyc2lvbilcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cbiAgICAgICAgICAgIH0pXG4gICAgICAgIH0sXG4gICAgICAgIHVwZGF0ZVN0YXRlOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICBsZXQgc3RhdGUgPSB7fVxuXG4gICAgICAgICAgICBzdGF0ZVsnYXJyYW5nZXMnXSA9IHRoaXMuYXJyYW5nZXM7XG4gICAgICAgICAgICBzdGF0ZVsncmVjdXJzaW9ucyddID0gdGhpcy5yZWN1cnNpb25zO1xuXG4gICAgICAgICAgICB0aGlzLnN0YXRlID0gc3RhdGVcbiAgICAgICAgfSxcbiAgICAgICAgYXJyYW5nZVRlbXBsYXRlOiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICByZXR1cm4ge1xuICAgICAgICAgICAgICAgIHRlbXBfaWQ6IHRoaXMudGVtcFJhbmRvbSgpLFxuICAgICAgICAgICAgICAgIG5hbWU6IFwiXCIsXG4gICAgICAgICAgICAgICAgb3JkZXJfY29sdW1uOiB0aGlzLmFycmFuZ2VzLmxlbmd0aCxcbiAgICAgICAgICAgICAgICBjaGlsZHJlbjogW11cbiAgICAgICAgICAgIH07XG4gICAgICAgIH0sXG4gICAgICAgIGNoaWxkcmVuQXJyYW5nZVRlbXBsYXRlOiBmdW5jdGlvbiAoaW5kZXgpIHtcbiAgICAgICAgICAgIHJldHVybiB7XG4gICAgICAgICAgICAgICAgdGVtcF9pZDogdGhpcy50ZW1wUmFuZG9tKCksXG4gICAgICAgICAgICAgICAgbmFtZTogXCJcIixcbiAgICAgICAgICAgICAgICBpbWFnZTogXCJcIixcbiAgICAgICAgICAgICAgICBvcmRlcl9jb2x1bW46IHRoaXMuYXJyYW5nZXNbaW5kZXhdLmNoaWxkcmVuLmxlbmd0aFxuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICAvLyBcdTgxRUFcdTUyQThcdTUyMURcdTU5Q0JcdTUzMTYgYXJyYW5nZXNcbiAgICAgICAgYXV0b0ZpcnN0OiBmdW5jdGlvbiAoKSB7XG4gICAgICAgICAgICB0aGlzLmFycmFuZ2VzID0gW3RoaXMuYXJyYW5nZVRlbXBsYXRlKCldXG4gICAgICAgICAgICB0aGlzLmFycmFuZ2VzWzBdLmNoaWxkcmVuLnB1c2godGhpcy5jaGlsZHJlbkFycmFuZ2VUZW1wbGF0ZSgwKSlcbiAgICAgICAgfSxcbiAgICAgICAgLy8gXHU2REZCXHU1MkEwXHU0RTNCXHU4OUM0XHU2ODNDXG4gICAgICAgIGFkZEFycmFuZ2U6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIHRoaXMuYXJyYW5nZXMucHVzaCh0aGlzLmFycmFuZ2VUZW1wbGF0ZSgpKTtcbiAgICAgICAgfSxcbiAgICAgICAgLy8gXHU2REZCXHU1MkEwXHU1QjUwXHU4OUM0XHU2ODNDXG4gICAgICAgIGFkZENoaWxkcmVuQXJyYW5nZTogZnVuY3Rpb24oaW5kZXgpIHtcbiAgICAgICAgICAgIHRoaXMuYXJyYW5nZXNbaW5kZXhdLmNoaWxkcmVuLnB1c2godGhpcy5jaGlsZHJlbkFycmFuZ2VUZW1wbGF0ZShpbmRleCkpO1xuXG4gICAgICAgICAgICBpZiAodGhpcy5hcnJhbmdlc1tpbmRleF0uY2hpbGRyZW4ubGVuZ3RoID09IDEpIHsgICAgICAgIC8vIFx1NjVCMFx1NTJBMFx1NzY4NFx1NEUzQlx1ODlDNFx1NjgzQ1x1NTMwNVx1NTQyQlx1NEU4Nlx1NEUwMFx1NEUyQVx1NUI1MFx1ODlDNFx1NjgzQ1x1RkYwQ1x1NEUzQlx1ODlDNFx1NjgzQ1x1NzUxRlx1NjU0OFxuICAgICAgICAgICAgICAgIHRoaXMucmVjdXJzaW9ucyA9IFtdOyAvLyBcdTg5QzRcdTY4M0NcdTU5MjdcdTUzRDhcdTUzMTZcdUZGMENcdTZFMDVcdTdBN0EgcmVjdXJzaW9uc1xuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICBkZWxldGVBcnJhbmdlOiBmdW5jdGlvbiAoaW5kZXgpIHtcbiAgICAgICAgICAgIC8vIFx1NTk4Mlx1Njc5Q1x1NTIyMFx1OTY2NFx1NzY4NFx1ODlDNFx1NjgzQ1x1NTMwNVx1NTQyQlx1NUI1MFx1ODlDNFx1NjgzQ1xuICAgICAgICAgICAgaWYgKHRoaXMuYXJyYW5nZXNbaW5kZXhdLmNoaWxkcmVuLmxlbmd0aCkge1xuICAgICAgICAgICAgICAgIHRoaXMucmVjdXJzaW9ucyA9IFtdOyAvLyBcdTg5QzRcdTY4M0NcdTU5MjdcdTUzRDhcdTUzMTZcdUZGMENcdTZFMDVcdTdBN0EgcmVjdXJzaW9uc1xuICAgICAgICAgICAgfVxuXG4gICAgICAgICAgICB0aGlzLmFycmFuZ2VzLnNwbGljZShpbmRleCwgMSk7XG4gICAgICAgIH0sXG4gICAgICAgIC8vIFx1NTIyMFx1OTY2NFx1NUI1MFx1ODlDNFx1NjgzQ1xuICAgICAgICBkZWxldGVDaGlsZHJlbkFycmFuZ2U6IGZ1bmN0aW9uIChwYXJlbnRJbmRleCwgaW5kZXgpIHtcbiAgICAgICAgICAgIGxldCBkYXRhID0gdGhpcy5hcnJhbmdlc1twYXJlbnRJbmRleF0uY2hpbGRyZW5baW5kZXhdO1xuICAgICAgICAgICAgXG4gICAgICAgICAgICAvLyBcdTc2RjRcdTYzQTVcdTVDMDZcdTVCNTBcdTg5QzRcdTY4M0NcdTUyMjBcdTk2NjRcbiAgICAgICAgICAgIHRoaXMuYXJyYW5nZXNbcGFyZW50SW5kZXhdLmNoaWxkcmVuLnNwbGljZShpbmRleCwgMSk7XG5cbiAgICAgICAgICAgIGlmICh0aGlzLmFycmFuZ2VzW3BhcmVudEluZGV4XS5jaGlsZHJlbi5sZW5ndGggPD0gMCkge1xuICAgICAgICAgICAgICAgIC8vIFx1NUY1M1x1NTI0RFx1ODlDNFx1NjgzQ1x1OTg3OVx1RkYwQ1x1NjI0MFx1NjcwOVx1NUI1MFx1ODlDNFx1NjgzQ1x1OTBGRFx1ODhBQlx1NTIyMFx1OTY2NFx1RkYwQ1x1NkUwNVx1N0E3QSByZWN1cnNpb25zXG4gICAgICAgICAgICAgICAgdGhpcy5yZWN1cnNpb25zID0gW107ICAgICAgICAvLyBcdTg5QzRcdTY4M0NcdTU5MjdcdTUzRDhcdTUzMTZcdUZGMENcdTZFMDVcdTdBN0EgcmVjdXJzaW9uc1xuICAgICAgICAgICAgfSBlbHNlIHtcbiAgICAgICAgICAgICAgICAvLyBcdTY3RTVcdThCRTIgcmVjdXJzaW9ucyBcdTRFMkRcdTUzMDVcdTU0MkJcdTg4QUJcdTUyMjBcdTk2NjRcdTc2ODRcdTc2ODRcdTVCNTBcdTg5QzRcdTY4M0NcdTc2ODRcdTk4NzlcdUZGMENcdTcxMzZcdTU0MEVcdTc5RkJcdTk2NjRcbiAgICAgICAgICAgICAgICBsZXQgZGVsZXRlUmVjdXJzaW9uSW5kZXhBcnIgPSBbXTtcbiAgICAgICAgICAgICAgICB0aGlzLnJlY3Vyc2lvbnMuZm9yRWFjaCgocmVjdXJzaW9uLCBpbmRleCkgPT4ge1xuICAgICAgICAgICAgICAgICAgICByZWN1cnNpb24uYXJyYW5nZV90ZXh0cy5mb3JFYWNoKChhcnJhbmdlX3RleHQsIGl4KSA9PiB7XG4gICAgICAgICAgICAgICAgICAgICAgICBpZiAoYXJyYW5nZV90ZXh0ID09IGRhdGEubmFtZSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGRlbGV0ZVJlY3Vyc2lvbkluZGV4QXJyLnB1c2goaW5kZXgpO1xuICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICBkZWxldGVSZWN1cnNpb25JbmRleEFyci5zb3J0KGZ1bmN0aW9uIChhLCBiKSB7XG4gICAgICAgICAgICAgICAgICAgIHJldHVybiBiIC0gYTtcbiAgICAgICAgICAgICAgICB9KTtcbiAgICAgICAgICAgICAgICAvLyBcdTc5RkJcdTk2NjRcdTY3MDlcdTc2RjhcdTUxNzNcdTVCNTBcdTg5QzRcdTY4M0NcdTc2ODRcdTk4NzlcbiAgICAgICAgICAgICAgICBkZWxldGVSZWN1cnNpb25JbmRleEFyci5mb3JFYWNoKChyZWN1cnNpb25JbmRleCwgaW5kZXgpID0+IHtcbiAgICAgICAgICAgICAgICAgICAgLy8gcmVjdXJzaW9uSW5kZXggXHU0RTNBXHU4OTgxXHU1MjIwXHU5NjY0XHU3Njg0IHJlY3Vyc2lvbnMgXHU3Njg0IGluZGV4XG4gICAgICAgICAgICAgICAgICAgIHRoaXMucmVjdXJzaW9ucy5zcGxpY2UocmVjdXJzaW9uSW5kZXgsIDEpO1xuICAgICAgICAgICAgICAgIH0pO1xuICAgICAgICAgICAgfVxuICAgICAgICB9LFxuICAgICAgICAvLyBcdTkxQ0RcdTY1QjBcdTY3ODRcdTVFRkEgcmVjdXJzaW9ucyBcdTg4NjhcdTY4M0NcbiAgICAgICAgYnVpbGRSZWN1cnNpb25zVGFibGU6IGZ1bmN0aW9uICgpIHtcbiAgICAgICAgICAgIGxldCBhcnJhbmdlQ2hpbGRyZW5JZEFyciA9IFtdO1xuXG4gICAgICAgICAgICB0aGlzLmFycmFuZ2VzLmZvckVhY2goKGFycmFuZ2UsIGtleSkgPT4ge1xuICAgICAgICAgICAgICAgIGxldCBjaGlsZHJlbiA9IGFycmFuZ2UuY2hpbGRyZW47XG4gICAgICAgICAgICAgICAgbGV0IGNoaWxkcmVuSWRBcnIgPSBbXTtcbiAgICAgICAgICAgICAgICBcbiAgICAgICAgICAgICAgICBpZiAoY2hpbGRyZW4ubGVuZ3RoID4gMCkge1xuICAgICAgICAgICAgICAgICAgICBjaGlsZHJlbi5mb3JFYWNoKChjaGlsZCwgaykgPT4ge1xuICAgICAgICAgICAgICAgICAgICAgICAgY2hpbGRyZW5JZEFyci5wdXNoKGNoaWxkLnRlbXBfaWQpO1xuICAgICAgICAgICAgICAgICAgICB9KTtcblxuICAgICAgICAgICAgICAgICAgICBhcnJhbmdlQ2hpbGRyZW5JZEFyci5wdXNoKGNoaWxkcmVuSWRBcnIpXG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfSk7XG5cbiAgICAgICAgICAgIHRoaXMucmVjdXJzaW9uRnVuYyhhcnJhbmdlQ2hpbGRyZW5JZEFycik7XG4gICAgICAgIH0sXG4gICAgICAgIC8vIFx1OTAxMlx1NUY1MlxuICAgICAgICByZWN1cnNpb25GdW5jOiBmdW5jdGlvbiAoYXJyYW5nZUNoaWxkcmVuSWRBcnIsIGFycmFuZ2VLID0gMCwgdGVtcCA9IFtdKSB7XG4gICAgICAgICAgICBpZiAoYXJyYW5nZUsgPT0gYXJyYW5nZUNoaWxkcmVuSWRBcnIubGVuZ3RoICYmIGFycmFuZ2VLICE9IDApIHtcbiAgICAgICAgICAgICAgICBsZXQgdGVtcERldGFpbCA9IFtdO1xuICAgICAgICAgICAgICAgIGxldCB0ZW1wRGV0YWlsSWRzID0gW107XG5cbiAgICAgICAgICAgICAgICB0ZW1wLmZvckVhY2goKGl0ZW0sIGluZGV4KSA9PiB7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMuYXJyYW5nZXMuZm9yRWFjaCgoYXJyYW5nZSwgaW54KSA9PiB7XG4gICAgICAgICAgICAgICAgICAgICAgICBhcnJhbmdlLmNoaWxkcmVuLmZvckVhY2goKGNoaWxkLCBpeCkgPT4ge1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgIGlmIChpdGVtID09IGNoaWxkLnRlbXBfaWQpIHtcbiAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgdGVtcERldGFpbC5wdXNoKGNoaWxkLm5hbWUpO1xuICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICB0ZW1wRGV0YWlsSWRzLnB1c2goY2hpbGQudGVtcF9pZCk7XG4gICAgICAgICAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgICAgICAgICAgfSlcbiAgICAgICAgICAgICAgICAgICAgfSlcbiAgICAgICAgICAgICAgICB9KVxuXG4gICAgICAgICAgICAgICAgbGV0IGZsYWcgPSBmYWxzZTsgLy8gXHU5RUQ4XHU4QkE0XHU2REZCXHU1MkEwXHU2NUIwXHU3Njg0XG4gICAgICAgICAgICAgICAgZm9yIChsZXQgaSBpbiB0aGlzLnJlY3Vyc2lvbnMpIHtcbiAgICAgICAgICAgICAgICAgICAgLy8gXHU0RkREXHU4QkMxXHU2NTcwXHU3RUM0XHU2NjJGXHU1NDBDXHU0RTAwXHU0RTJBXHU5ODdBXHU1RThGXG4gICAgICAgICAgICAgICAgICAgIHRoaXMucmVjdXJzaW9uc1tpXS5hcnJhbmdlX3RlbXBfaWRzLnNvcnQoKTtcbiAgICAgICAgICAgICAgICAgICAgdGVtcERldGFpbElkcy5zb3J0KCk7XG5cbiAgICAgICAgICAgICAgICAgICAgaWYgKHRoaXMucmVjdXJzaW9uc1tpXS5hcnJhbmdlX3RlbXBfaWRzLmpvaW4oJywnKSA9PSB0ZW1wRGV0YWlsSWRzLmpvaW4oJywnKSkge1xuICAgICAgICAgICAgICAgICAgICAgICAgZmxhZyA9IGk7XG4gICAgICAgICAgICAgICAgICAgICAgICBicmVhaztcbiAgICAgICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgICAgIH1cblxuICAgICAgICAgICAgICAgIGlmICghZmxhZykge1xuICAgICAgICAgICAgICAgICAgICBsZXQgcHVzaFJlY3Vyc2lvbiA9IHtcbiAgICAgICAgICAgICAgICAgICAgICAgIHRlbXBfaWQ6IHRoaXMudGVtcFJhbmRvbSgxMDAwMDAwMCwgOTk5OTk5OTkpLFxuICAgICAgICAgICAgICAgICAgICAgICAgYXJyYW5nZV90ZXh0czogdGVtcERldGFpbCxcbiAgICAgICAgICAgICAgICAgICAgICAgIGFycmFuZ2VfdGVtcF9pZHM6IHRlbXBEZXRhaWxJZHMsXG4gICAgICAgICAgICAgICAgICAgIH07XG5cbiAgICAgICAgICAgICAgICAgICAgLy8gXHU1QzA2IHJlY3Vyc2lvbiBcdTc2ODRcdTgxRUFcdTVCOUFcdTRFNDlcdTVCNTdcdTZCQjVcdTUyMURcdTU5Q0JcdTUzMTZcbiAgICAgICAgICAgICAgICAgICAgdGhpcy50YWJsZUZpZWxkcy5mb3JFYWNoKChmaWVsZCkgPT4ge1xuICAgICAgICAgICAgICAgICAgICAgICAgcHVzaFJlY3Vyc2lvbltmaWVsZC5maWVsZF0gPSBmaWVsZC5kZWZhdWx0XG4gICAgICAgICAgICAgICAgICAgIH0pXG5cbiAgICAgICAgICAgICAgICAgICAgLy8gXHU1MjFEXHU1OUNCXHU1MzE2XHU4RjZDXHU2MzYyXHU1QjU3XHU2QkI1XG4gICAgICAgICAgICAgICAgICAgIHB1c2hSZWN1cnNpb25bdGhpcy5hcnJhbmdlVG9SZWN1cnNpb25LZXldID0gW107XG5cbiAgICAgICAgICAgICAgICAgICAgdGhpcy5yZWN1cnNpb25zLnB1c2gocHVzaFJlY3Vyc2lvbilcbiAgICAgICAgICAgICAgICB9IGVsc2Uge1xuICAgICAgICAgICAgICAgICAgICB0aGlzLnJlY3Vyc2lvbnNbZmxhZ10uYXJyYW5nZV90ZXh0cyA9IHRlbXBEZXRhaWw7XG4gICAgICAgICAgICAgICAgICAgIHRoaXMucmVjdXJzaW9uc1tmbGFnXS5hcnJhbmdlX3RlbXBfaWRzID0gdGVtcERldGFpbElkcztcbiAgICAgICAgICAgICAgICB9XG4gICAgICAgICAgICB9XG5cbiAgICAgICAgICAgIGlmIChhcnJhbmdlQ2hpbGRyZW5JZEFyci5sZW5ndGgpIHtcbiAgICAgICAgICAgICAgICBhcnJhbmdlQ2hpbGRyZW5JZEFyclthcnJhbmdlS10gJiYgYXJyYW5nZUNoaWxkcmVuSWRBcnJbYXJyYW5nZUtdLmZvckVhY2goKGN2LCBjaykgPT4ge1xuICAgICAgICAgICAgICAgICAgICB0ZW1wW2FycmFuZ2VLXSA9IGFycmFuZ2VDaGlsZHJlbklkQXJyW2FycmFuZ2VLXVtja107XG5cbiAgICAgICAgICAgICAgICAgICAgdGhpcy5yZWN1cnNpb25GdW5jKGFycmFuZ2VDaGlsZHJlbklkQXJyLCBhcnJhbmdlSyArIDEsIHRlbXApO1xuICAgICAgICAgICAgICAgIH0pXG4gICAgICAgICAgICB9XG4gICAgICAgIH0sXG4gICAgICAgIHRlbXBSYW5kb206IGZ1bmN0aW9uIChtaW4gPSAxMDAwMDAwLCBtYXggPSA5OTk5OTk5KSB7XG4gICAgICAgICAgICBsZXQgcmFuZG9tO1xuXG4gICAgICAgICAgICBkbyB7XG4gICAgICAgICAgICAgICAgcmFuZG9tID0gTWF0aC5mbG9vcihNYXRoLnJhbmRvbSgpICogKG1heCAtIG1pbiArIDEpKSArIG1pbjtcbiAgICAgICAgICAgIH0gd2hpbGUgKHRoaXMuZXhpc3RSYW5kb21zLmluY2x1ZGVzKHJhbmRvbSkpO1xuXG4gICAgICAgICAgICB0aGlzLmV4aXN0UmFuZG9tcy5wdXNoKHJhbmRvbSk7ICAgICAgLy8gXHU0RkREXHU1QjU4XHVGRjBDXHU5MDdGXHU1MTREXHU5MUNEXHU1OTBEXG5cbiAgICAgICAgICAgIHJldHVybiByYW5kb207XG4gICAgICAgIH1cbiAgICB9XG59XG4iXSwKICAibWFwcGluZ3MiOiAiO0FBQWUsU0FBUixpQkFBa0MsRUFBRSxPQUFPLHVCQUF1QixZQUFZLEdBQUc7QUFDcEYs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
