import {observable, action, configure} from "mobx";

configure({
  enforceActions: 'always'
});

class Store {
  @observable currentId = 3;

  @observable layout = [
    {
      i: '1',
      x: 0,
      y: 0,
      w: 12,
      h: 3
    },
    {
      i: '2',
      x: 1,
      y: 2,
      w: 12,
      h: 3
    },
    {
      i: '3',
      x: 2,
      y: 3,
      w: 12,
      h: 3
    },
  ];

  @observable attrs = {
    1: {
      component: 'Swiper',
    },
    2: {
      component: 'GoodsList',
      type: 'grid',
      title: '新品推荐',
      data: 'best'
    },
    3: {
      component: 'GoodsList',
      type: 'list',
      title: '火爆热销',
      data: 'hot'
    }
  };

  @action.bound changeLayout(layout) {
    this.layout = layout;
  }

  @action.bound add(attr) {
    this.layout.push({
      i: (++this.currentId).toString(),
      x: 2,
      y: 3,
      w: 12,
      h: 3
    });
    this.attrs[this.currentId] = attr;
  }
}

export default new Store()