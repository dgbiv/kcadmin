import React, {Component} from 'react';
import {
    SortableContainer,
    SortableElement,
    SortableHandle,
    arrayMove,
} from 'react-sortable-hoc';
import PropTypes from 'prop-types'
import Select from 'react-select';
import axios from 'axios'

const DragHandle = SortableHandle(() => (
    <div className='drag-handle glyphicon glyphicon-menu-hamburger' />
)); // This can be any component you want

class Part extends React.Component {
    static propTypes = {
        name: PropTypes.string,
        title: PropTypes.string,
        onChangeTitle: PropTypes.func,
        onChangeType: PropTypes.func
    };

    render() {
        const {name, title, type, onChangeTitle, onChangeType} = this.props;
        const typeImageTable = [
            '/img/scroll.png',
            '/img/grid.png',
            '/img/list.png',
        ];
        const options = [
            { value: 1, label: '横排(右滚动)' },
            { value: 2, label: '多列(三列)' },
            { value: 3, label: '竖排(下滚动)' }
        ];

        return (
            <div className="part">
                <div className="part__handle">
                    <input type="text" value={title} onChange={onChangeTitle} />
                    <Select
                        value={options[type - 1]}
                        onChange={onChangeType}
                        options={options}
                        isSearchable={false}
                    />
                </div>
                <div className="part__content">
                    <div className="part__header">
                        <div className="title">{title}</div>
                        <div className="more">查看更多></div>
                    </div>
                    <img src={typeImageTable[type - 1]} alt=""/>
                </div>
            </div>
        )
    }
}

const SortableItem = SortableElement(({value}) => {
    return (
        <li className='sortable-item'>
            <DragHandle />
            {value}
        </li>
    );
});

const SortableList = SortableContainer(({items}) => {
    return (
        <ul>
            {items.map((value, index) => (
                <SortableItem key={`item-${index}`} index={index} value={value} />
            ))}
        </ul>
    );
});

class SortableComponent extends Component {
    state = {
        items: data,
    };

    onSortEnd = ({oldIndex, newIndex}) => {
        const {items} = this.state;

        this.setState({
            items: arrayMove(items, oldIndex, newIndex),
        });
    };

    onChangeTitle = (index, event) => {
        let {items} = this.state;
        items[index].title = event.target.value;
        this.setState({
            items
        })
    };

    onChangeType = (index, newType) => {
        let {items} = this.state;
        items[index].type = newType.value;
        this.setState({
            items
        })
    };

    submit = () => {
        const {items} = this.state;
        axios.post(location.href, {
            '_csrf-api': csrf,
            data: items
        }).then((res) => {
            if (res.data === 1) {
                this.back();
            }
        })
    };

    back = () => {
        history.go(-1)
    };

    render() {
        const {items} = this.state;

        const itemsView = items.map((item, index) => (
            <Part
                key={index}
                title={item.title}
                type={item.type}
                onChangeTitle={this.onChangeTitle.bind(undefined, index)}
                onChangeType={this.onChangeType.bind(undefined, index)}
            />
        ));

        return (
            <div>
                <SortableList
                    items={itemsView}
                    lockAxis='y'
                    onSortEnd={this.onSortEnd}
                    useDragHandle={true}
                />
                <button type='button' className='btn btn-success' onClick={this.submit}>保存</button>
                <button className='btn btn-info' onClick={this.back}>返回</button>
            </div>
        );
    }
}