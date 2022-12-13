import React from "react";
import {Table} from "antd";

const Customer = ()=> {

    const dataSource = [
        {
            key: '1',
            name: 'Mike',
            age: 32,
            address: '10 Downing Street',
        },
        {key: '2',
            name: 'John',
            age: 42,
            address: '10 Downing Street',
        },
    ];


    for (let i = 0; i < 46; i++) {
        dataSource.push({
            name: `Edward King ${i}`,
            key: i,
            age: 32,
            address: `London, Park Lane no. ${i}`,
        });
    }


    const columns = [
        {
            title: 'Name',
            dataIndex: 'name',
            key: 'name',
        },
        {
            title: 'Age',
            dataIndex: 'age',
            key: 'age',
        },
        {
            title: 'Address',
            dataIndex: 'address',
            key: 'address',
        },
    ];


    return <Table dataSource={dataSource} columns={columns} />;

}

export default Customer;
