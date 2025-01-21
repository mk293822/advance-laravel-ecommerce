import { Config } from "ziggy-js";
import navbar from "@/Components/app/Navbar";

export interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at?: string;
  stripe_account_active: boolean;
  vendor: {
    status: string;
    status_label: string;
    store_name: string;
    store_address: string;
    cover_image: string;
  };
}

export type Image = {
  id: number;
  thumb: string;
  small: string;
  large: string;
};

export type VariationTypeOption = {
  id: number;
  name: string;
  images: Image[];
  type: VariationType;
};

export type VariationType = {
  id: number;
  name: string;
  type: "select" | "radio" | "image";
  options: VariationTypeOption[];
};

export type Product = {
  id: number;
  title: string;
  slug: string;
  price: number;
  quantity: number;
  image: string;
  images: Image[];
  description: string;
  short_description: string;
  meta_description: string;
  meta_title: string;
  user: {
    name: string;
    id: number;
    store_name: string;
  };
  department: {
    id: number;
    name: string;
    slug: string;
  };
  variationTypes: VariationType[];
  variations: Array<{
    id: number;
    variation_type_option_ids: number[];
    price: number;
    quantity: number;
  }>;
};

export type CartItems = {
  option_ids: any;
  title: string;
  product_id: number;
  id: number;
  price: number;
  quantity: number;
  slug: string;
  image: string;
  options_ids: Record<string, number>;
  options: VariationTypeOption[];
};

export type PaginationProps<T> = {
  data: Array<T>;
};

export type GroupedCartItems = {
  user: User;
  items: CartItems[];
  totalQuantity: number;
  totalPrice: number;
};

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>
> = T & {
  auth: {
    user: User;
  };
  appName: string;
  ziggy: Config & { location: string };
  csrf_token: string;
  totalQuantity: number;
  totalPrice: number;
  miniCartItems: CartItems[];
  error: string;
  keyword: string;
  departments: Department[];
  success: {
    message: string;
    time: number;
  };
};

export type OrderItem = {
  id: number;
  quantity: number;
  price: number;
  variation_type_option_ids: number[];
  product: {
    id: number;
    title: string;
    slug: string;
    description: string;
    image: string;
  };
};

export type Order = {
  id: number;
  total_price: number;
  status: string;
  created_at: string;
  vendorUser: {
    id: string;
    name: string;
    email: string;
    store_name: string;
    store_address: string;
  };
  orderItems: OrderItem[];
};

export type Vendor = {
  id: number;
  store_name: string;
  store_address: string;
};

export type Category = [id: number, name: string];

export type Department = {
  id: number;
  name: string;
  slug: string;
  meta_title: string;
  meta_description: string;
  categories: Category[];
};
