import {Link, usePage} from '@inertiajs/react';
import {Breadcrumb} from "antd";

export default function Breadcrumbs() {
    const {breadcrumbs} = usePage().props
    return (
        <Breadcrumb items={
            breadcrumbs.map(item => ({
                "title": (item.link ? <Link href={item.link}>{item.title}</Link> : '')
            }))
        }/>
    );
}
