import React from 'react';
import ReactDOM from 'react-dom';
import 'antd/dist/reset.css';
import { Button, Space } from 'antd';
import Customer from "./customer/Customer";



function App() {
    return (
        <div>
        <Space wrap>
            <Button type="primary">Primary Button</Button>
            <Button>Default Button</Button>
            <Button type="dashed">Dashed Button</Button>
            <Button type="text">Text Button</Button>
            <Button type="link">Link Button</Button>

        </Space>
            <Customer/>
        </div>
    );
}

export default App;

if (document.getElementById('app')) {
    ReactDOM.render(<App />, document.getElementById('app'));
}
