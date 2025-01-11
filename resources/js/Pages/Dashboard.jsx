import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, usePage} from '@inertiajs/react';
import AiForm from "@/Pages/AiForm.jsx";
import {Badge, Divider} from "antd";
import Text from "antd/es/typography/Text";
import Breadcrumbs from "@/Components/Breadcrumbs";


export default function Dashboard({msg}) {
    const { errors } = usePage().props
    return (
        <AuthenticatedLayout header={<Breadcrumbs/>}>
            <Head title="Dashboard"/>
            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <Divider dashed style={{ borderColor: '#ef4444' }}>
                                <Text className="text-lg">Structured
                                    {' '}<span className="p-2 bg-gray-100 border border-gray-100 rounded rounded-tl-2xl rounded-br-2xl">OR</span>{' '}Chat with AI
                                </Text>
                            </Divider>
                            <AiForm className="max-w-xl"/>
                            {errors.promptInput && <div className="pt-5 text-sm text-red-500">{errors.promptInput}</div>}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
