import Checkbox from "@/Components/core/Checkbox";
import InputError from "@/Components/core/InputError";
import InputLabel from "@/Components/core/InputLabel";
import PrimaryButton from "@/Components/core/PrimaryButton";
import TextInput from "@/Components/core/TextInput";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, Link, useForm } from "@inertiajs/react";
import { FormEventHandler } from "react";

export default function Login({
  status,
  canResetPassword,
}: {
  status?: string;
  canResetPassword: boolean;
}) {
  const { data, setData, post, processing, errors, reset } = useForm({
    email: "",
    password: "",
    remember: false,
  });

  const submit: FormEventHandler = (e) => {
    e.preventDefault();

    post(route("login"), {
      onFinish: () => reset("password"),
    });
  };

  return (
    <AuthenticatedLayout>
      <Head title="Log in" />

      <div className="p-8 my-8">
        <div className="card bg-white/10 shadow max-w-[420px] mx-auto">
          <div className="card-body">
            {status && (
              <div className="mb-4 text-sm font-medium text-green-600">
                {status}
              </div>
            )}

            <form onSubmit={submit}>
              <div>
                <InputLabel htmlFor="email" value="Email" />

                <TextInput
                  id="email"
                  type="email"
                  name="email"
                  value={data.email}
                  className="mt-1 block w-full"
                  autoComplete="username"
                  isFocused={true}
                  onChange={(e) => setData("email", e.target.value)}
                />

                <InputError message={errors.email} className="mt-2" />
              </div>

              <div className="mt-4">
                <InputLabel htmlFor="password" value="Password" />

                <TextInput
                  id="password"
                  type="password"
                  name="password"
                  value={data.password}
                  className="mt-1 block w-full"
                  autoComplete="current-password"
                  onChange={(e) => setData("password", e.target.value)}
                />

                <InputError message={errors.password} className="mt-2" />
              </div>

              <div className="mt-4 block">
                <label className="flex items-center">
                  <Checkbox
                    name="remember"
                    checked={data.remember}
                    onChange={(e) => setData("remember", e.target.checked)}
                  />
                  <span className="ms-2 text-sm text-gray-600 dark:text-gray-400">
                    Remember me
                  </span>
                </label>
              </div>

              <div className="mt-4 flex items-center justify-between">
                <Link href={route("register")} className="link">
                  Sign up
                </Link>
                <div className="flex justify-center items-center">
                  {canResetPassword && (
                    <Link href={route("password.request")} className="link">
                      Forgot your password?
                    </Link>
                  )}

                  <PrimaryButton className="ms-4" disabled={processing}>
                    Log in
                  </PrimaryButton>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}
