import {useEffect, useState} from "react";
import {Transition} from '@headlessui/react';
import {useForm} from '@inertiajs/react';
import {Button, Col, Form, Input, Row, Spin, Switch} from 'antd';
import {CheckOutlined} from "@ant-design/icons";
import Markdown from "react-markdown";
import remarkGfm from "remark-gfm";
import rehypeRaw from "rehype-raw";
import {Prism as SyntaxHighlighter} from "react-syntax-highlighter";
import {oneLight} from "react-syntax-highlighter/dist/cjs/styles/prism/index.js";

const {TextArea} = Input;

export default function AiForm() {
    const [loading, setLoading] = useState(false);
    const [msg, setMsg] = useState('');
    const [switchToggle, setSwitchToggle] = useState(true);
    const {data, setData, post, processing, recentlySuccessful} = useForm({
        promptInput: '',
        switch: true,
    });


    async function sendAi(e) {
        e.preventDefault();
        setLoading(true);

        // For now always use streamed output, by setting switch to true at the beginning
        if (data.switch) {
            post('/stream', {
                // preserveState: true, // Prevent modal or page reload
                preserveScroll: true,
                data,
                onSuccess: (res) => {
                    setMsg('')
                    const eventSource = new EventSource('/stream');
                    eventSource.onmessage = function (event) {
                        const response = JSON.parse(event.data.trim());
                        // Append the response chunk to your UI
                        setMsg(prev => prev
                            // .replace(/\\n{2,3}/g, '<br/>')
                            // .replace(/\\n+/g, '<br/>')
                            + response.msg
                                .replace(/\\u([\dA-Fa-f]{4})/g, (_, code) =>
                                    String.fromCharCode(parseInt(code, 16))
                                )
                                .replace(/\\n+/g, '\n')
                        )
                    }
                    eventSource.onerror = e => eventSource.close()
                },
                onFinish: () => {
                    setLoading(false);
                }
            })
        } else {
            post(route('ai.send'), {
                // preserveState: true, // Prevent modal or page reload
                preserveScroll: true,
                data,
                onSuccess: (res) => {
                    setMsg(res.props.msg)
                },
                onFinish: () => {
                    setLoading(false);
                }
            })
        }
    }


    return (
        <section>
            <Row>
                <Col className="gutter-row" span={24}>
                    <Spin spinning={loading}>
                        <Form onSubmit={sendAi} className="mt-6 space-y-6">
                            <div>
                                <Form.Item>
                                    <TextArea placeholder="Press Enter + Shift to send "
                                              allowClear
                                              showCount={true}
                                              maxLength={2000}
                                              rows={10}
                                              onChange={e => setData('promptInput', e.target.value)}
                                              onKeyDown={e => {
                                                  if (e.key === 's' && e.ctrlKey && !e.shiftKey) {
                                                      setSwitchToggle(prev => {
                                                          const p = !prev
                                                          setData('switch', p)
                                                          return p
                                                      })
                                                  }

                                                  if (e.code === 'Enter' && e.shiftKey) {
                                                      sendAi(e)
                                                  }
                                              }}
                                    />
                                </Form.Item>
                            </div>

                            {/*<Row>
                                <Col span={2}>
                                    <div>
                                        <Form.Item label="Stream" name="switch" valuePropName="checked" >
                                            <Switch className="p-0 m-0" value={switchToggle} onChange={(state, e) => setData('switch', state)}/>
                                        </Form.Item>
                                    </div>
                                </Col>
                                <Col span={3} className="align-middle">
                                    <div className="pt-2 pl-4 text-xs flex items-center">
                                                <svg viewBox="0 0 16 16" version="1.1" width="20" xmlns="http://www.w3.org/2000/svg" fill="#000000">
                                                    <g id="SVGRepo_bgCarrier" strokeWidth="0"></g><g
                                                    id="SVGRepo_tracerCarrier" strokeLinecap="round"
                                                    strokeLinejoin="round"></g><g id="SVGRepo_iconCarrier"> <path
                                                    fill="#444"
                                                    d="M9 7v-1h-1v-1h-1v1h-0.5v1h0.5v3.56c0.176 0.835 0.907 1.453 1.783 1.453 0.077 0 0.152-0.005 0.226-0.014l-0.009-0.999c-0.055 0.012-0.119 0.019-0.185 0.019-0.359 0-0.669-0.21-0.813-0.514l-0.002-3.505h1z"></path> <path
                                                    fill="#444" d="M14 3h1v9h-1v-9z"></path>
                                                    <path fill="#444"
                                                          d="M13 6c-0.025-0.001-0.055-0.001-0.085-0.001-0.773 0-1.462 0.358-1.911 0.917l-0.004-0.915h-1v6h1v-3c-0.003-0.037-0.004-0.080-0.004-0.124 0-1.038 0.842-1.88 1.88-1.88 0.044 0 0.087 0.001 0.13 0.004l-0.006-1z"></path>
                                                    <path fill="#444"
                                                          d="M4.19 12c-2.030 0-3.19-1.46-3.19-4s1.16-4 3.19-4c0.009-0 0.019-0 0.029-0 0.539 0 1.052 0.114 1.515 0.32l-0.424 0.901c-0.319-0.139-0.69-0.22-1.080-0.22-0.014 0-0.028 0-0.042 0-1.808-0-2.188 1.63-2.188 3s0.38 3 2.19 3c0.497-0.013 0.96-0.145 1.366-0.368l0.444 0.898c-0.524 0.285-1.146 0.458-1.806 0.47z"></path> </g>
                                                </svg>
                                                <svg viewBox="0 0 24 24" width="20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <g id="SVGRepo_bgCarrier" strokeWidth="0"></g><g
                                                    id="SVGRepo_tracerCarrier" strokeLinecap="round"
                                                    strokeLinejoin="round"></g><g id="SVGRepo_iconCarrier"> <path
                                                    d="M9 12H15" stroke="#323232" strokeWidth="2" strokeLinecap="round"
                                                    strokeLinejoin="round"></path> <path d="M12 9L12 15"
                                                                                         stroke="#323232"
                                                                                         strokeWidth="2"
                                                                                         strokeLinecap="round"
                                                                                         strokeLinejoin="round"></path> </g></svg>
                                                <span>S</span>
                                            </div>
                                </Col>
                            </Row>*/}
                            <Row>
                                <Col className="gutter-row" span={2}>
                                    <Button onClick={sendAi} loading={processing} type="default">Send</Button>
                                </Col>
                                <Col span={1}>
                                    <Transition
                                        show={recentlySuccessful}
                                        enter="transition ease-in-out"
                                        enterFrom="opacity-0"
                                        leave="transition ease-in-out"
                                        leaveTo="opacity-0"
                                    >
                                        <p className="text-sm text-green-700">
                                            <CheckOutlined/>
                                        </p>
                                    </Transition>
                                </Col>
                            </Row>
                        </Form>
                    </Spin>
                </Col>
            </Row>
            <Row>
                <Col span={24}>
                    <div className="pt-5 mt-5 border-t border-dashed border-gray-100">
                        <code>
                            <Markdown
                                remarkPlugins={[remarkGfm]}
                                rehypePlugins={[rehypeRaw]}
                                components={{
                                    code({node, inline, className, children, ...props}) {
                                        const match = /language-(\w+)/.exec(className || '');

                                        return !inline && match ? (
                                            <SyntaxHighlighter
                                                style={oneLight}
                                                customStyle={{
                                                    transition: "max-width 1s linear"
                                                }}
                                                PreTag="div"
                                                language={match[1]} {...props}>
                                                {String(children).replace(/\n$/, '')}
                                            </SyntaxHighlighter>
                                        ) : (
                                            <code className={className} {...props}>
                                                {children}
                                            </code>
                                        );
                                    },
                                }}>
                                {msg}
                            </Markdown>
                        </code>
                    </div>
                </Col>
            </Row>
        </section>
    );
}