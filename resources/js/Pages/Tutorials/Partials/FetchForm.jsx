import {Field, Fieldset, Input, Label, Legend, Textarea} from "@headlessui/react";
import {useRef, useState} from "react";
import {Button, Divider, List, Table} from "antd";
import { PoweroffOutlined, SyncOutlined } from '@ant-design/icons';

export default function FetchForm() {
    const host = 'https://jsonplaceholder.typicode.com'
    const [loading, setLoading] = useState(false);
    const [data, setData] = useState(null);
    const formRef = useRef();


    function handleSubmit(e) {
        e.preventDefault()
        setLoading(true);
        const form = new FormData(formRef.current);
        if (!form.get('title') || !form.get('message')) {
            setLoading(false);
            return;
        }

        // Reset only the 'message' field
        const messageField = formRef.current.querySelector('[name="message"]');
        if (messageField) {
            messageField.value = ""
        }

        http.post(`${host}/posts`, {
            title: form.get('title'),
            body: form.get('message'),
            userId: 1
        }).then(res => {
            setLoading(false);
            setData(res.data);
        });
    }

    return (
        <>
            <div className="grid grid-cols-[600px_500px] gap-4">
                <Fieldset disabled={loading} className="w-2/3 mx-auto shadow-lg  p-5 flex justify-center">
                    <form ref={formRef} onSubmit={handleSubmit} className="space-y-6   rounded-lg">
                        <Legend className="text-lg font-bold">Fetch Form</Legend>
                        <Field>
                            <Label className="block text-sm font-medium text-gray-700 mb-1">Title</Label>
                            <Input className="w-full border border-gray-100 rounded-lg p-3 focus:ring-1" type="te"
                                   name="title"/>
                        </Field>
                        <Field>
                            <Label className="block">Message</Label>
                            <Textarea className="w-full border border-gray-100 rounded-lg p-3 focus:ring-1"
                                      name="message"/>
                        </Field>
                        <Field>
                            <Button
                                variant="filled"
                                color="primary"
                                loading={loading && {icon: <SyncOutlined spin/>}}
                                iconPosition="end"
                                onClick={handleSubmit}
                            >
                                Submit
                            </Button>
                        </Field>
                    </form>
                </Fieldset>


                <div className="p-5 w-full">
                    {data && <List
                        size="small"
                        bordered
                        dataSource={[data]}
                        renderItem={(item) => <List.Item><strong>ID: </strong> {item.id} | <strong>Title: </strong> {item.title} | <strong>Body:</strong> {item.body} | <strong>User ID:</strong> {item.userId}</List.Item>}
                    />}
                </div>
            </div>
        </>
    )
}