import React from 'react';
import ReactDOM from 'react-dom';
import 'antd/dist/antd.css';
import {Button} from 'antd';

function Example() {
    return (
        <div className="container">
            <Button type="primary">Primary Button</Button>
        </div>
    );
}

export default Example;

if (document.getElementById('app')) {
    ReactDOM.render(<Example />, document.getElementById('app'));
}
